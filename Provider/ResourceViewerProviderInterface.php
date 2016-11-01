<?php

namespace Toro\Bundle\CmsBundle\Provider;

use Toro\Bundle\CmsBundle\Model\ViewerableInterface;

interface ResourceViewerProviderInterface
{
    /**
     * @param ViewerableInterface $resource
     */
    public function fireEvent(ViewerableInterface $resource);

    /**
     * @param ViewerableInterface $resource
     */
    public function increase(ViewerableInterface $resource);
}
