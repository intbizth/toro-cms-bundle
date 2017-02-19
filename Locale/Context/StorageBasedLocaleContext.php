<?php

namespace Toro\Bundle\CmsBundle\Locale\Context;

use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Channel\Context\ChannelNotFoundException;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Sylius\Component\Locale\Context\LocaleNotFoundException;
use Sylius\Component\Locale\Provider\LocaleProviderInterface;
use Toro\Bundle\CmsBundle\Locale\LocaleStorageInterface;

final class StorageBasedLocaleContext implements LocaleContextInterface
{
    /**
     * @var ChannelContextInterface
     */
    private $channelContext;

    /**
     * @var LocaleStorageInterface
     */
    private $localeStorage;

    /**
     * @var LocaleProviderInterface
     */
    private $localeProvider;

    /**
     * @var string
     */
    private $defaultLocale;

    /**
     * @param ChannelContextInterface $channelContext
     * @param LocaleStorageInterface $localeStorage
     * @param LocaleProviderInterface $localeProvider
     * @param string $defaultLocale
     */
    public function __construct(
        ChannelContextInterface $channelContext,
        LocaleStorageInterface $localeStorage,
        LocaleProviderInterface $localeProvider,
        $defaultLocale
    ) {
        $this->channelContext = $channelContext;
        $this->localeStorage = $localeStorage;
        $this->localeProvider = $localeProvider;
        $this->defaultLocale = $defaultLocale;
    }

    /**
     * {@inheritdoc}
     */
    public function getLocaleCode()
    {
        $availableLocalesCodes = $this->localeProvider->getAvailableLocalesCodes();

        try {
            $localeCode = $this->localeStorage->get($this->channelContext->getChannel());
        } catch (ChannelNotFoundException $exception) {
            throw new LocaleNotFoundException(null, $exception);
        } catch (\Exception $exception) {
            return $this->defaultLocale;
        }

        if (!in_array($localeCode, $availableLocalesCodes, true)) {
            throw LocaleNotFoundException::notAvailable($localeCode, $availableLocalesCodes);
        }

        return $localeCode;
    }
}
