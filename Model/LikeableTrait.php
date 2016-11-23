<?php

namespace Toro\Bundle\CmsBundle\Model;

use Sylius\Component\Resource\Model\TimestampableTrait;

trait LikeableTrait
{
    /**
     * @var int
     */
    protected $likeTotal = 0;

    /**
     * @var int
     */
    protected $dislikeTotal = 0;

    /**
     * {@inheritdoc}
     */
    public function like()
    {
        $this->likeTotal++;
    }

    /**
     * {@inheritdoc}
     */
    public function dislike()
    {
        $this->dislikeTotal++;
    }

    /**
     * {@inheritdoc}
     */
    public function unlike()
    {
        $this->likeTotal--;

        if ($this->likeTotal < 0) {
            $this->likeTotal = 0;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function undislike()
    {
        $this->dislikeTotal--;

        if ($this->dislikeTotal < 0) {
            $this->dislikeTotal = 0;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getLikeTotal()
    {
        return $this->likeTotal;
    }

    /**
     * {@inheritdoc}
     */
    public function setLikeTotal($likeTotal)
    {
        $this->likeTotal = $likeTotal;
    }

    /**
     * {@inheritdoc}
     */
    public function getDislikeTotal()
    {
        return $this->dislikeTotal;
    }

    /**
     * {@inheritdoc}
     */
    public function setDislikeTotal($dislikeTotal)
    {
        $this->dislikeTotal = $dislikeTotal;
    }
}
