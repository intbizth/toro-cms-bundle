<?php

namespace Toro\Bundle\CmsBundle\Model;

use Sylius\Component\Resource\Model\TimestampableTrait;
use Sylius\Component\Resource\Model\TranslatableTrait;
use Symfony\Cmf\Bundle\MediaBundle\ImageInterface;
use Toro\Bundle\MediaBundle\Meta\MediaReference;

/**
 * @method PostTranslation getTranslation($local = null)
 */
class Post implements PostInterface
{
    use OptionableTrait;
    use TimestampableTrait;
    use ViewerableTrait;

    use TranslatableTrait {
        __construct as private initializeTranslationsCollection;
    }

    /**
     * @var int
     */
    protected $id;

    /**
     * @var boolean
     */
    protected $published = true;

    /**
     * @var \DateTime
     */
    protected $publishedAt;

    /**
     * @var string
     */
    protected $type = self::TYPE_CONTENT;

    /**
     * @var ImageInterface
     */
    protected $cover;

    /**
     * @var string
     */
    protected $coverId;

    public function __construct()
    {
        $this->initializeTranslationsCollection();
    }

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
    public function getSlug()
    {
        return $this->getTranslation()->getSlug();
    }

    /**
     * {@inheritdoc}
     */
    public function setSlug($slug = null)
    {
        $this->getTranslation()->setSlug($slug);
    }

    /**
     * {@inheritdoc}
     */
    public function getTitle()
    {
        return $this->getTranslation()->getTitle();
    }

    /**
     * {@inheritdoc}
     */
    public function setTitle($title)
    {
        $this->getTranslation()->setTitle($title);
    }

    /**
     * {@inheritdoc}
     */
    public function getBody()
    {
        return $this->getTranslation()->getBody();
    }

    /**
     * {@inheritdoc}
     */
    public function setBody($body)
    {
        $this->getTranslation()->setBody($body);
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription()
    {
        return $this->getTranslation()->getDescription();
    }

    /**
     * {@inheritdoc}
     */
    public function setDescription($description)
    {
        $this->getTranslation()->setDescription($description);
    }

    /**
     * {@inheritdoc}
     */
    public function isPublished()
    {
        return $this->published;
    }

    /**
     * {@inheritdoc}
     */
    public function setPublished($published)
    {
        $this->published = $published;
    }

    /**
     * {@inheritdoc}
     */
    public function getCompileContent()
    {
        return $this->getBody();
    }

    /**
     * {@inheritdoc}
     */
    public function getPublishedAt()
    {
        return $this->publishedAt;
    }

    /**
     * {@inheritdoc}
     */
    public function setPublishedAt(\DateTime $publishedAt =  null)
    {
        $this->publishedAt = $publishedAt;
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * {@inheritdoc}
     */
    public function getCover()
    {
        return $this->cover;
    }

    /**
     * {@inheritdoc}
     */
    public function setCover(ImageInterface $cover = null)
    {
        $this->cover = $cover;

        // `logo` no mapped for doctrine
        // we need to trig some field for doctrine changed tracker
        $this->updatedAt = new \DateTime();
    }

    /**
     * {@inheritdoc}
     */
    public function getMediaMetaReferences()
    {
        return array(
            new MediaReference('/post-' . $this->id, 'coverId', $this->coverId, $this->cover),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getVdoPath()
    {
        return $this->getTranslation()->getVdoPath();
    }

    /**
     * {@inheritdoc}
     */
    public function setVdoPath($vdoPath)
    {
        $this->getTranslation()->setVdoPath($vdoPath);
    }

    /**
     * {@inheritdoc}
     */
    protected function createTranslation()
    {
        return new PostTranslation();
    }
}
