<?php

namespace Toro\Bundle\CmsBundle\Locale\Handler;

use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Channel\Context\ChannelNotFoundException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Toro\Bundle\CmsBundle\Exception\HandleException;
use Toro\Bundle\CmsBundle\Locale\Events;
use Toro\Bundle\CmsBundle\Locale\LocaleStorageInterface;

final class LocaleChangeHandler implements LocaleChangeHandlerInterface
{
    /**
     * @var LocaleStorageInterface
     */
    private $localeStorage;

    /**
     * @var ChannelContextInterface
     */
    private $channelContext;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @param LocaleStorageInterface $localeStorage
     * @param ChannelContextInterface $channelContext
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        LocaleStorageInterface $localeStorage,
        ChannelContextInterface $channelContext,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->localeStorage = $localeStorage;
        $this->channelContext = $channelContext;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function handle($code)
    {
        try {
            $this->localeStorage->set($this->channelContext->getChannel(), $code);
        } catch (ChannelNotFoundException $exception) {
            throw new HandleException(self::class, 'Toro could not find the channel.', $exception);
        }

        $this->eventDispatcher->dispatch(Events::CODE_CHANGED, new GenericEvent($code));
    }
}
