<?php

namespace Toro\Bundle\CmsBundle\Model;

use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\SlugAwareInterface;
use Sylius\Component\Resource\Model\TimestampableInterface;
use Sylius\Component\Resource\Model\TranslatableInterface;

interface PostInterface extends
    CompileAwareContentInterface,
    TimestampableInterface,
    ResourceInterface,
    TranslatableInterface,
    OptionableInterface,
    SlugAwareInterface,
    ViewerableInterface
{
    const TYPE_CONTENT = 'content';
    const TYPE_YOUTUBE = 'youtube';

    /**
     * @return string
     */
    public function getTitle();

    /**
     * @param string $title
     */
    public function setTitle($title);

    /**
     * @return string
     */
    public function getBody();

    /**
     * @param string $body
     */
    public function setBody($body);

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

    /**
     * @return string
     */
    public function getType();

    /**
     * @param string $type
     */
    public function setType($type);
}
