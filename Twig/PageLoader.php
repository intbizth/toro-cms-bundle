<?php

namespace Toro\Bundle\CmsBundle\Twig;

use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\TranslatorInterface;
use Toro\Bundle\CmsBundle\Doctrine\ORM\PageRepositoryInterface;
use Toro\Bundle\CmsBundle\Model\CompileAwareContentInterface;
use Toro\Bundle\CmsBundle\Model\PageInterface;

class PageLoader implements \Twig_LoaderInterface, \Twig_ExistsLoaderInterface
{
    use ContainerAwareTrait;

    private $preg = "/^(Toro)(.*)/";

    /**
     * @var \Twig_LoaderInterface
     */
    private $decoratedLoader;

    /**
     * @var PageRepositoryInterface
     */
    private $pageRepository;

    /**
     * @var LocaleContextInterface
     */
    private $localeContext;

    /**
     * @var ChannelContextInterface
     */
    private $channelContext;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var array
     */
    private $cache = [];

    /**
     * @var array
     */
    private $templates = [];

    public function __construct(
        \Twig_LoaderInterface $decoratedLoader,
        PageRepositoryInterface $pageRepository,
        LocaleContextInterface $localeContext = null,
        ChannelContextInterface $channelContext = null,
        RequestStack $requestStack = null,
        $preg = null
    ) {
        $this->decoratedLoader = $decoratedLoader;
        $this->pageRepository = $pageRepository;
        $this->localeContext = $localeContext;
        $this->channelContext = $channelContext;
        $this->requestStack = $requestStack;

        if ($preg) {
            $this->preg = $preg;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getSourceContext($name): \Twig_Source
    {
        try {
            return $this->findTemplate($name)->getOptions()->getTemplating();
        } catch (\Exception $exception) {
            return $this->decoratedLoader->getSourceContext($name);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCacheKey($name): string
    {
        try {
            return $this->findTemplate($name)->getId();
        } catch (\Exception $exception) {
            return $this->decoratedLoader->getCacheKey($name);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isFresh($name, $time)
    {
        try {
            return $this->findTemplate($name)
                    ->getOptions()->getUpdatedAt()
                    ->getTimestamp() <= $time
                ;
        } catch (\Exception $exception) {
            return $this->decoratedLoader->isFresh($name, $time);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function exists($name)
    {
        try {
            $this->findTemplate($name);

            return true;
        } catch (\Exception $exception) {
            return $this->decoratedLoader->exists($name);
        }
    }

    /**
     * @param TemplateReferenceInterface|string $template
     *
     * @return PageInterface
     */
    private function findTemplate($template)
    {
        $logicalName = (string)$template;
        $this->isSupported($logicalName);
        $slug = $this->getSlug();

        if (array_key_exists($slug, $this->cache)) {
            if ($this->templates[$slug] === $logicalName && false !== $this->cache[$slug]) {
                $this->addTwigGlobalVar($this->cache[$slug]);

                return $this->cache[$slug];
            }

            throw new \InvalidArgumentException();
        }

        $this->templates[$slug] = $logicalName;
        $this->cache[$slug] = false;
        $this->cache[$slug] = $page = $this->findPage($slug);

        $this->addTwigGlobalVar($page);

        return $page;
    }

    private function addTwigGlobalVar(PageInterface $page)
    {
        $twig = $this->container->get('twig');

        if ($page->getOptions()->isTranslatable()) {
            $this->addTranslations($page->getOptions()->getTranslation());
        }

        if ($page instanceof CompileAwareContentInterface) {
            try {
                $pageContent = (string) $page->getCompileContent();

                if (!empty(strip_tags($pageContent))) {
                    $pageContent = $twig->createTemplate($pageContent)->render([]);
                }
            } catch (\Twig_Error_Runtime $e) {
                $pageContent = $e->getRawMessage();
            }
        }

        $twig->addGlobal('page', $page);
        $twig->addGlobal('page_content', $pageContent);
        $twig->addGlobal('template_style', $page->getOptions()->getStyle());
        $twig->addGlobal('template_script', $page->getOptions()->getScript());
    }

    /**
     * @return null|string
     */
    private function getSlug()
    {
        return $this->requestStack->getCurrentRequest()
            ? $this->requestStack->getCurrentRequest()->getPathInfo()
            : null;
    }

    /**
     * @param $logicalName
     *
     * @return bool
     */
    private function isSupported($logicalName)
    {
        if (!$this->requestStack->getParentRequest()) {
            throw new \LogicException();
        }

        if ($this->requestStack->getParentRequest() || 'html' === $this->requestStack->getCurrentRequest()->getRequestFormat()) {
            throw new \LogicException();
        }

        if ($this->getSlug() && preg_match($this->preg, trim($logicalName)) > 0) {
            return true;
        }

        throw new \InvalidArgumentException();
    }

    /**
     * @param $slug
     *
     * @return PageInterface
     */
    private function findPage($slug)
    {
        $criteria = [
            'published' => true,
            'slug' => $slug,
        ];

        if ($this->localeContext && $this->localeContext->getLocaleCode()) {
            $criteria['locale'] = $this->localeContext->getLocaleCode();
        }

        if ($this->channelContext && $this->channelContext->getChannel()) {
            $criteria['channel'] = $this->channelContext->getChannel();
        }

        $page = $this->pageRepository->findOneBy($criteria);

        if ($page && $page->getOptions()) {
            return $page;
        }

        throw new \InvalidArgumentException();
    }



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
     *
     * @return string
     */
    private function transformLocale(TranslatorInterface $translator)
    {
        $theme = $this->container->get('sylius.context.theme')->getTheme();
        $locale = $translator->getLocale();

        if (null === $theme) {
            return $locale;
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
        $translator = $this->container->get('translator');

        foreach ($translations as $code => $messages) {
            if ($this->isValidLocal($code) && $code === $this->localeContext->getLocaleCode()) {
                $this->flatten($messages);
                $translator->getCatalogue($this->transformLocale($translator))->add($messages);
            }
        }
    }
}
