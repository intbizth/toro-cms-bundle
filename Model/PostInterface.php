<?php

namespace Toro\Bundle\CmsBundle\Model;

use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\SlugAwareInterface;
use Sylius\Component\Resource\Model\TimestampableInterface;
use Sylius\Component\Resource\Model\TranslatableInterface;
use Symfony\Cmf\Bundle\MediaBundle\ImageInterface;
use Toro\Bundle\MediaBundle\Model\MediaAwareInterface;

interface PostInterface extends
    CompileAwareContentInterface,
    TimestampableInterface,
    ResourceInterface,
    TranslatableInterface,
    OptionableInterface,
    SlugAwareInterface,
    ViewerableInterface,
    PostTranslationInterface,
    MediaAwareInterface,
    LikeableInterface,
    PublishableInterface
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
     * @return string
     */
    public function getType();

    /**
     * @param string $type
     */
    public function setType($type);

    /**
     * @return string
     */
    public function getCoverId();

    /**
     * @return ImageInterface
     */
    public function getCover();

    /**
     * @param ImageInterface|null $cover
     */
    public function setCover(ImageInterface $cover = null);
}
