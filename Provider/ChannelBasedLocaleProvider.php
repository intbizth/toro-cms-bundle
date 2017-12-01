<?php

namespace Toro\Bundle\CmsBundle\Provider;

use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Channel\Context\ChannelNotFoundException;
use Sylius\Component\Channel\Model\ChannelInterface;
use Sylius\Component\Locale\Model\LocaleInterface;
use Sylius\Component\Locale\Provider\LocaleProviderInterface;

final class ChannelBasedLocaleProvider implements LocaleProviderInterface
{
    /**
     * @var ChannelContextInterface
     */
    private $channelContext;

    /**
     * @var string
     */
    private $defaultLocaleCode;

    /**
     * @param ChannelContextInterface $channelContext
     * @param string $defaultLocaleCode
     */
    public function __construct(ChannelContextInterface $channelContext, $defaultLocaleCode)
    {
        $this->channelContext = $channelContext;
        $this->defaultLocaleCode = $defaultLocaleCode;
    }

    /**
     * {@inheritdoc}
     */
    public function getAvailableLocalesCodes(): array
    {
        try {
            /** @var ChannelInterface $channel */
            $channel = $this->channelContext->getChannel();

            return $channel
                ->getLocales()
                ->map(function (LocaleInterface $locale) {
                    return $locale->getCode();
                })
                ->toArray();
        } catch (ChannelNotFoundException $exception) {
            return [$this->defaultLocaleCode];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultLocaleCode(): string
    {
        try {
            /** @var ChannelInterface $channel */
            $channel = $this->channelContext->getChannel();

            return $channel->getDefaultLocale()->getCode();
        } catch (ChannelNotFoundException $exception) {
            return $this->defaultLocaleCode;
        }
    }
}
