<?php

namespace Toro\Bundle\CmsBundle\Model;

interface PublishableInterface
{
    /**
     * @return boolean
     */
    public function isPublished();

    /**
     * @param boolean $published
     */
    public function setPublished($published);

    /**
     * @return \DateTime
     */
    public function getPublishedAt();

    /**
     * @param \DateTime $publishedAt
     */
    public function setPublishedAt(\DateTime $publishedAt =  null);
}
