<?php

namespace Toro\Bundle\CmsBundle\Model;

use Sylius\Component\Channel\Model\ChannelInterface as BaseChannelInterface;

trait ChannelableTrait
{
    /**
     * @var BaseChannelInterface
     */
    protected $channel;

    /**
     * {@inheritdoc}
     */
    public function getChannel(): ?BaseChannelInterface
    {
        return $this->channel;
    }

    /**
     * {@inheritdoc}
     */
    public function setChannel(BaseChannelInterface $channel = null): void
    {
        $this->channel = $channel;
    }
}
