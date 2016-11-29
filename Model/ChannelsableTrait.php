<?php

namespace Toro\Bundle\CmsBundle\Model;

use Doctrine\Common\Collections\Collection;
use Sylius\Component\Channel\Model\ChannelInterface as BaseChannelInterface;

trait ChannelsableTrait
{
    /**
     * @var Collection|BaseChannelInterface[]
     */
    protected $channels;

    /**
     * @return Collection|BaseChannelInterface[]
     */
    public function getChannels()
    {
        return $this->channels;
    }

    /**
     * @param Collection $channels
     */
    public function setChannels(Collection $channels)
    {
        $this->channels = $channels;
    }

    /**
     * @param BaseChannelInterface $channel
     */
    public function addChannel(BaseChannelInterface $channel)
    {
        if (!$this->hasChannel($channel)) {
            $this->channels->add($channel);
        }
    }

    /**
     * @param BaseChannelInterface $channel
     */
    public function removeChannel(BaseChannelInterface $channel)
    {
        if ($this->hasChannel($channel)) {
            $this->channels->removeElement($channel);
        }
    }

    /**
     * @param BaseChannelInterface $channel
     *
     * @return bool
     */
    public function hasChannel(BaseChannelInterface $channel)
    {
        return $this->channels->contains($channel);
    }
}
