<?php

namespace Toro\Bundle\CmsBundle\Locale;

use Sylius\Component\Locale\Context\LocaleContextInterface;
use Symfony\Component\HttpKernel\CacheWarmer\WarmableInterface;
use Symfony\Component\Translation\InvalidArgumentException;
use Symfony\Component\Translation\MessageCatalogueInterface;
use Symfony\Component\Translation\TranslatorBagInterface;
use Symfony\Component\Translation\TranslatorInterface;

class ThemeAwareTranslator implements TranslatorInterface, TranslatorBagInterface, WarmableInterface
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var LocaleContextInterface
     */
    private $localeContext;

    /**
     * @param TranslatorInterface|TranslatorBagInterface $translator
     * @param LocaleContextInterface $localeContext
     */
    public function __construct(TranslatorInterface $translator, LocaleContextInterface $localeContext)
    {
        $this->translator = $translator;
        $this->localeContext = $localeContext;
    }

    /**
     * {@inheritdoc}
     */
    public function trans($id, array $parameters = array(), $domain = null, $locale = null)
    {
        return $this->translator->trans($id, $parameters, $domain, $locale ?: $this->getLocale());
    }

    /**
     * {@inheritdoc}
     */
    public function transChoice($id, $number, array $parameters = array(), $domain = null, $locale = null)
    {
        return $this->translator->transChoice($id, $number, $parameters, $domain, $locale ?: $this->getLocale());
    }

    /**
     * {@inheritdoc}
     */
    public function getLocale()
    {
        return $this->localeContext->getLocaleCode();
    }

    /**
     * {@inheritdoc}
     */
    public function setLocale($locale)
    {
        $this->translator->setLocale($locale);
    }

    /**
     * {@inheritdoc}
     */
    public function getCatalogue($locale = null)
    {
        return $this->translator->getCatalogue($locale);
    }

    /**
     * {@inheritdoc}
     */
    public function warmUp($cacheDir)
    {
        if ($this->translator instanceof WarmableInterface) {
            $this->translator->warmUp($cacheDir);
        }
    }

    /**
     * Passes through all unknown calls onto the translator object.
     *
     * @param string $method
     * @param array $arguments
     *
     * @return mixed
     */
    public function __call($method, array $arguments)
    {
        $translator = $this->translator;
        $arguments = array_values($arguments);

        return $translator->$method(...$arguments);
    }
}
