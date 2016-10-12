<?php

namespace Toro\Bundle\CmsBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Sylius\Component\Channel\Model\ChannelInterface;
use Sylius\Component\Resource\Model\TimestampableTrait;
use Sylius\Component\Resource\Model\TranslatableTrait;
use Symfony\Component\Security\Core\User\UserInterface;

class Page implements PageInterface
{
    use OptionableTrait;
    use TimestampableTrait;

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
     * @var boolean
     */
    protected $partial = true;

    /**
     * @var ChannelInterface
     */
    protected $channel;

    /**
     * @var UserInterface
     */
    private $createdBy;

    /**
     * @var UserInterface
     */
    private $updatedBy;

    public function __construct()
    {
        $this->initializeTranslationsCollection();

        $this->routes = new ArrayCollection();
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
        return $this->translate()->getSlug();
    }

    /**
     * {@inheritdoc}
     */
    public function setSlug($slug = null)
    {
        $this->translate()->setSlug($slug);
    }

    /**
     * {@inheritdoc}
     */
    public function getTitle()
    {
        return $this->translate()->getTitle();
    }

    /**
     * {@inheritdoc}
     */
    public function setTitle($title)
    {
        $this->translate()->setTitle($title);
    }

    /**
     * {@inheritdoc}
     */
    public function getBody()
    {
        return $this->translate()->getBody();
    }

    /**
     * {@inheritdoc}
     */
    public function setBody($body)
    {
        $this->translate()->setBody($body);
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
    public function isPartial(): bool
    {
        return $this->partial;
    }

    /**
     * {@inheritdoc}
     */
    public function setPartial(bool $partial)
    {
       $this->partial = $partial;
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
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * {@inheritdoc}
     */
    public function setChannel(ChannelInterface $channel = null)
    {
        $this->channel = $channel;
    }

    /**
     * Sets createdBy.
     *
     * @param  UserInterface $createdBy
     */
    public function setCreatedBy(UserInterface $createdBy)
    {
        $this->createdBy = $createdBy;
    }

    /**
     * Returns createdBy.
     *
     * @return UserInterface
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Sets updatedBy.
     *
     * @param  UserInterface $updatedBy
     */
    public function setUpdatedBy(UserInterface $updatedBy)
    {
        $this->updatedBy = $updatedBy;
    }

    /**
     * Returns updatedBy.
     *
     * @return UserInterface
     */
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }
}
