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
     * @return void
     */
    public function increaseViewer();

    /**
     * @return boolean
     */
    public function isViewerEnabled();
}
