<?php

namespace Toro\Bundle\CmsBundle\Model;

use Sylius\Component\Resource\Model\TimestampableTrait;

abstract class Like implements LikeInterface
{
    use TimestampableTrait;

    /**
     * @var int
     */
    protected $id;

    /**
     * @var bool
     */
    protected $liked = true;

    /**
     * @var LikeableInterface
     */
    protected $likeable;

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function isLiked()
    {
        return $this->liked;
    }

    /**
     * {@inheritdoc}
     */
    public function setLiked($liked)
    {
        $this->liked = $liked;
    }

    /**
     * {@inheritdoc}
     */
    public function getLikeable()
    {
        return $this->likeable;
    }

    /**
     * {@inheritdoc}
     */
    public function setLikeable(LikeableInterface $likeable = null)
    {
        $this->likeable = $likeable;
    }
}
