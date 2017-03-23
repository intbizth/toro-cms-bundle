<?php

namespace Toro\Bundle\CmsBundle\Model;

use Sylius\Component\Resource\Model\TimestampableTrait;

abstract class Flagged implements FlaggedInterface
{
    use TimestampableTrait;

    /**
     * @var int
     */
    protected $id;

    /**
     * @var FlaggedTypeInterface
     */
    protected $type;

    /**
     * @var integer
     */
    protected $position = 0;

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
    public function getType()
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public function setType(FlaggedTypeInterface $type = null)
    {
        $this->type = $type;
    }

    /**
     * {@inheritdoc}
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * {@inheritdoc}
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }
}
