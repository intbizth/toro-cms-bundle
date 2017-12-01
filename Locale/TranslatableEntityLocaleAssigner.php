<?php

namespace Toro\Bundle\CmsBundle\Locale;

use Sylius\Component\Locale\Context\LocaleContextInterface;
use Sylius\Component\Locale\Context\LocaleNotFoundException;
use Sylius\Component\Resource\Model\TranslatableInterface;
use Sylius\Component\Resource\Translation\Provider\TranslationLocaleProviderInterface;
use Sylius\Component\Resource\Translation\TranslatableEntityLocaleAssignerInterface;

final class TranslatableEntityLocaleAssigner implements TranslatableEntityLocaleAssignerInterface
{
    /**
     * @var LocaleContextInterface
     */
    private $localeContext;

    /**
     * @var TranslationLocaleProviderInterface
     */
    private $translationLocaleProvider;

    /**
     * @param LocaleContextInterface $localeContext
     * @param TranslationLocaleProviderInterface $translationLocaleProvider
     */
    public function __construct(
        LocaleContextInterface $localeContext,
        TranslationLocaleProviderInterface $translationLocaleProvider
    ) {
        $this->localeContext = $localeContext;
        $this->translationLocaleProvider = $translationLocaleProvider;
    }

    /**
     * {@inheritdoc}
     */
    public function assignLocale(TranslatableInterface $translatableEntity): void
    {
        $fallbackLocale = $this->translationLocaleProvider->getDefaultLocaleCode();

        try {
            $currentLocale = $this->localeContext->getLocaleCode();
        } catch (LocaleNotFoundException $e) {
            $currentLocale = $fallbackLocale;
        }

        $translatableEntity->setCurrentLocale($currentLocale);
        $translatableEntity->setFallbackLocale($fallbackLocale);
    }
}
