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
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * {@inheritdoc}
     */
    public function setChannel(BaseChannelInterface $channel = null)
    {
        $this->channel = $channel;
    }
}
