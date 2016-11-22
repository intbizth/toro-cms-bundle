<?php

namespace Toro\Bundle\CmsBundle\Locale\Storage;

use Sylius\Component\Channel\Model\ChannelInterface;
use Sylius\Component\Locale\Context\LocaleNotFoundException;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Toro\Bundle\CmsBundle\Locale\LocaleStorageInterface;

final class SessionBasedLocaleStorage implements LocaleStorageInterface
{
    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @param SessionInterface $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * {@inheritdoc}
     */
    public function set(ChannelInterface $channel, $localeCode)
    {
        $this->session->set($this->provideKey($channel), $localeCode);
    }

    /**
     * {@inheritdoc}
     */
    public function get(ChannelInterface $channel)
    {
        $localeCode = $this->session->get($this->provideKey($channel));
        if (null === $localeCode) {
            throw new LocaleNotFoundException('No locale is set for current channel!');
        }

        return $localeCode;
    }

    /**
     * {@inheritdoc}
     */
    private function provideKey(ChannelInterface $channel)
    {
        return '_locale_' . $channel->getCode();
    }
}
