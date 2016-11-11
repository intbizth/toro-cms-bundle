<?php

namespace Toro\Bundle\CmsBundle\Controller;

use FOS\RestBundle\View\View;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TimestampableInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\TranslatorInterface;
use Toro\Bundle\CmsBundle\Doctrine\ORM\PageFinderRepositoryInterface;
use Toro\Bundle\CmsBundle\Model\CompileAwareContentInterface;
use Toro\Bundle\CmsBundle\Model\OptionableInterface;
use Toro\Bundle\CmsBundle\Model\PageInterface;

class PageController extends ResourceController
{
    /**
     * @var PageFinderRepositoryInterface
     */
    protected $repository;

    /**
     * @param string $locale
     *
     * @return bool
     */
    private function isValidLocal($locale)
    {
        if (1 !== preg_match('/^[a-z0-9@_\\.\\-]*$/i', $locale)) {
            return false;
        }

        return true;
    }

    /**
     * @param TranslatorInterface $translator
     * @param $locale
     *
     * @return string
     */
    private function transformLocale(TranslatorInterface $translator, $locale)
    {
        $theme = $this->get('sylius.context.theme')->getTheme();

        if (null === $theme) {
            return $locale;
        }

        if (null === $locale) {
            $locale = $translator->getLocale();
        }

        $locale = $locale . '@' . str_replace('/', '-', $theme->getName());

        return $locale;
    }

    /**
     * @param array $messages
     * @param array|null $subnode
     * @param null $path
     */
    private function flatten(array &$messages, array $subnode = null, $path = null)
    {
        if (null === $subnode) {
            $subnode = &$messages;
        }

        foreach ($subnode as $key => $value) {
            if (is_array($value)) {
                $nodePath = $path ? $path.'.'.$key : $key;
                $this->flatten($messages, $value, $nodePath);
                if (null === $path) {
                    unset($messages[$key]);
                }
            } elseif (null !== $path) {
                $messages[$path.'.'.$key] = $value;
            }
        }
    }

    /**
     * @param array $translations
     */
    private function addTranslations(array $translations)
    {
        /** @var TranslatorInterface|Translator $translator */
        $translator = $this->get('translator');

        foreach ($translations as $code => $messages) {
            if ($this->isValidLocal($code)) {
                $this->flatten($messages);
                $translator->getCatalogue($this->transformLocale($translator, $code))->add($messages);
            }
        }
    }

    /**
     * @param string $slug
     * @param boolean $partial
     *
     * @return PageInterface|OptionableInterface|TimestampableInterface|ResourceInterface
     */
    private function findPage($slug, $partial)
    {
        return $this->repository->findPageForDisplay([
            'slug' => $slug,
            'partial' => $partial,
            'published' => true,
            'locale' => $this->get('sylius.context.locale')->getLocaleCode(),
            'channel' => $this->get('sylius.context.channel')->getChannel(),
        ]);
    }

    public function partialAction(Request $request, $slug)
    {
        if (!$page = $this->findPage($slug, true)) {
            return Response::create();
        }

        return self::viewAction($request, $page);
    }

    public function viewAction(Request $request, $slug)
    {
        $page = is_object($slug) ? $slug : $this->findPage($slug, false);

        if (!$page) {
            throw new NotFoundHttpException("No page found");
        }

        $template = $request->get('template');
        $templateStrategy = null;
        $templateContent = null;
        $pageContent = null;
        $templateVar = $this->metadata->getName();

        // FIXME: with some cool idea!
        if ($page instanceof PageInterface) {
            if ($request->getBaseUrl() !== '/app_dev.php') {
                $page->setBody(
                    preg_replace('|/app_dev.php|', $request->getBaseUrl(), $page->getBody())
                );
            }

            if ($page->isPartial()) {
                $templateStrategy = 'partial';
            }
        }

        if ($page instanceof CompileAwareContentInterface) {
            try {
                $pageContent = $this->get('twig')->createTemplate($page->getCompileContent())->render([]);
            } catch (\Twig_Error_Runtime $e) {
                $pageContent = $e->getRawMessage();
            }
        }

        if ($option = $page->getOptions()) {
            $template = $option->getTemplate() ?: $template;
            $templateStrategy = $option->getTemplateStrategy() ?: $templateStrategy;
            $templateVar = $option->getTemplateVar($templateVar);

            if ($option->isTranslatable()) {
                $this->addTranslations($option->getTranslation());
            }

            if ($templating = trim($option->getTemplating())) {
                $templateContent = $this->get('twig')->createTemplate($templating)->render([
                    'page_content' => $pageContent,
                    $templateVar => $page
                ]);
            }
        }

        if ('blank' === $templateStrategy) {
            $template = 'ToroCmsBundle::blank.html.twig';
        }

        if (!$template) {
            throw new \LogicException("Empty template file, please config under your routing. ");
        }

        // increase viewer
        $this->get('toro_cms.provider.resource_viewer')->increase(
            $page, $request->get('manager', $this->get('toro.manager.page'))
        );

        $view = View::create()
            ->setTemplate($template)
            ->setData([
                $templateVar => $page,
                'page_content' => $pageContent,
                'template_content' => $templateContent,
                'template_style' => $option ? $option->getStyle() : null,
                'template_script' => $option ? $option->getScript() : null,
            ])
        ;

        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        return $this->viewHandler->handle($configuration, $view);
    }
}
