<?php

namespace Toro\Bundle\CmsBundle\Model;


interface LikeableInterface
{
    /**
     * Set like
     */
    public function like();

    /**
     * Set like
     */
    public function dislike();

    /**
     * Set like
     */
    public function unlike();

    /**
     * Set like
     */
    public function undislike();

    /**
     * @return int
     */
    public function getLikeTotal();

    /**
     * @param int $likeTotal
     */
    public function setLikeTotal($likeTotal);

    /**
     * @return int
     */
    public function getDislikeTotal();

    /**
     * @param int $dislikeTotal
     */
    public function setDislikeTotal($dislikeTotal);
}
