<?php

namespace Toro\Bundle\CmsBundle\Model;

use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TimestampableInterface;
use Sylius\Component\User\Model\UserAwareInterface;

interface ResourceViewerInterface extends UserAwareInterface, TimestampableInterface, ResourceInterface
{
    /**
     * @return string
     */
    public function getResourceName();

    /**
     * @param string $resourceName
     */
    public function setResourceName($resourceName);

    /**
     * @return int
     */
    public function getResourceId();

    /**
     * @param int $resourceId
     */
    public function setResourceId($resourceId);

    /**
     * @return string
     */
    public function getIp();

    /**
     * @param string $ip
     */
    public function setIp($ip);

    /**
     * @return array
     */
    public function getMeta();

    /**
     * @param array $meta
     */
    public function setMeta($meta);
}
