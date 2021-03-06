<?php

namespace Toro\Bundle\CmsBundle\EventListener;

use Sylius\Bundle\ChannelBundle\Doctrine\ORM\ChannelRepository;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Channel\Model\ChannelInterface;
use Sylius\Component\Resource\Exception\UnexpectedTypeException;

class ChannelDeletionListener
{
    /**
     * @var ChannelRepository
     */
    private $channelRepository;

    /**
     * @param ChannelRepository $repository
     */
    public function __construct(ChannelRepository $repository)
    {
        $this->channelRepository = $repository;
    }

    /**
     * Prevent channel deletion if no more channels enabled.
     *
     * @param ResourceControllerEvent $event
     */
    public function onChannelPreDelete(ResourceControllerEvent $event)
    {
        $channel = $event->getSubject();

        if (!$channel instanceof ChannelInterface) {
            throw new UnexpectedTypeException(
                $channel,
                ChannelInterface::class
            );
        }

        $results = $this->channelRepository->findBy(['enabled' => true]);

        if (!$results || (count($results) === 1 && current($results) === $channel)) {
            $event->stop('toro.ui.the_channel_cannot_be_deleted_at_least_one_enabled_channel_is_required');
        }
    }
}
