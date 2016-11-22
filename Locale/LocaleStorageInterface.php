<?php

namespace Toro\Bundle\CmsBundle\Locale;

use Sylius\Component\Channel\Context\ChannelNotFoundException;
use Sylius\Component\Channel\Model\ChannelInterface;

interface LocaleStorageInterface
{
    /**
     * @param ChannelInterface $channel
     * @param string $localeCode
     */
    public function set(ChannelInterface $channel, $localeCode);

    /**
     * @param ChannelInterface $channel
     *
     * @return string Locale code
     *
     * @throws ChannelNotFoundException
     */
    public function get(ChannelInterface $channel);
}
