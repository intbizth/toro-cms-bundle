<?php

namespace Toro\Bundle\CmsBundle\Controller;

use FOS\RestBundle\View\View;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TimestampableInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
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
     * @return bool
     */
    private function isDebug()
    {
        return $this->get('kernel')->isDebug();
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
            'locale' => $this->get('sylius.context.locale')->getLocaleCode(),
            'channel' => $this->get('sylius.context.channel')->getChannel(),
        ]);
    }

    /**
     * @param string $content
     * @param OptionableInterface $context
     *
     * @return string
     */
    private function printWidget($content, OptionableInterface $context)
    {
        if (preg_match_all('/\{\{\s?([a-zA-Z0-9_]+)\s?\}\}/', $content, $matches)) {
            $data = (array) $context->getOptions()->getData();
            $widgets = array_key_exists('widgets', $data) ? $data['widgets'] : [];
            $keys = array_keys($widgets);

            $widgetHolders = array_values(array_unique($matches[0]));
            $widgetNames = array_values(array_unique($matches[1]));
            $exitings = [];

            foreach ($widgetNames as $name) {
                if (in_array($name, $keys)) {
                    $exitings[] = $name;
                } else {
                    if ($this->isDebug()) {
                        throw new \LogicException('Not found widget named: ' . $name);
                    }
                }
            }

            foreach ($exitings as $i => $exiting) {
                if (!array_key_exists('name', $widgets[$exiting])) {
                    if ($this->isDebug()) {
                        throw new \LogicException('Required widget name for ' . $exiting);
                    }

                    continue;
                }

                $widgetName = $widgets[$exiting]['name'];
                $widgetOptions = array_key_exists('options', $widgets[$exiting])
                    ? json_encode($widgets[$exiting]['options'], JSON_UNESCAPED_UNICODE)
                    : null;

                $content = str_replace($widgetHolders[$i], sprintf('{{ %s(%s) }}', $widgetName, $widgetOptions), $content);
            }

            // clear holders
            foreach ($widgetHolders as $holder) {
                $content = str_replace($holder, '', $content);
            }
        }

        return $content;
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
            if (!is_string($slug) && !is_numeric($slug)) {
                $slug = '?';
            }

            throw new NotFoundHttpException("No page found - " . $slug);
        }

        $template = $request->get('template');
        $templateStrategy = null;
        $templateContent = null;
        $pageContent = null;
        $templateVar = $request->get('templateVar', $this->metadata->getName());

        // FIXME: with some cool idea!
        if ($page instanceof PageInterface) {
            if (!$this->isDebug()) {
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
                $pageContent = (string) $page->getCompileContent();

                if (!empty(strip_tags($pageContent))) {
                    $pageContent = $this->printWidget($pageContent, $page);
                    $pageContent = $this->get('twig')->createTemplate($pageContent)->render([
                        'context' => $page,
                    ]);
                }
            } catch (\Twig_Error_Loader $e) {
                if (!$this->get('kernel')->isDebug()) {
                    $pageContent = preg_replace('/\{\{(.*)\}\}/is', '', $pageContent);
                }
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

        $this->get('event_dispatcher')->dispatch('toro_cms.on_page_show', new GenericEvent($page, [
            'manager' => $request->get('manager', $this->get('toro.manager.page'))
        ]));

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
