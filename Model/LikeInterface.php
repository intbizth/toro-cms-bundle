<?php

namespace Toro\Bundle\CmsBundle\Model;


use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TimestampableInterface;

interface LikeInterface extends BlameableInterface, ResourceInterface, TimestampableInterface
{
    /**
     * @return boolean
     */
    public function isLiked();

    /**
     * @param boolean $liked
     */
    public function setLiked($liked);

    /**
     * @return LikeableInterface
     */
    public function getLikeable();

    /**
     * @param LikeableInterface $owner
     */
    public function setLikeable(LikeableInterface $owner = null);
}
