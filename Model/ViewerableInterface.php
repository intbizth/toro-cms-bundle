<?php

namespace Toro\Bundle\CmsBundle\Model;

use Sylius\Component\Resource\Model\ResourceInterface;

interface ViewerableInterface extends ResourceInterface
{
    /**
     * @return int
     */
    public function getViewers();

    /**
     * @param int $viewers
     */
    public function setViewers($viewers);

    /**
     * @return \DateTime|null
     */
    public function getLastViewerStampTime();

    /**
     * @param \DateTime|null $dateTime
     */
    public function setLastViewerStampTime(\DateTime $dateTime = null);

    /**
     * @return void
     */
    public function increaseViewer();

    /**
     * @return boolean
     */
    public function isViewerLogEnabled();

    /**
     * @return boolean
     */
    public function isViewerEnabled();
}
