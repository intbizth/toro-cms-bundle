<?php

namespace Toro\Bundle\CmsBundle\Model;

use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TimestampableInterface;

interface FlaggedInterface extends ResourceInterface, TimestampableInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @return FlaggedTypeInterface
     */
    public function getType();

    /**
     * @param FlaggedTypeInterface $type
     */
    public function setType(FlaggedTypeInterface $type = null);

    /**
     * @return int
     */
    public function getPosition();

    /**
     * @param int $position
     */
    public function setPosition($position);
}
