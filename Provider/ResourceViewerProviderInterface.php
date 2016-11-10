<?php

namespace Toro\Bundle\CmsBundle\Provider;

use Doctrine\Common\Persistence\ObjectManager;
use Toro\Bundle\CmsBundle\Model\ViewerableInterface;

interface ResourceViewerProviderInterface
{
    /**
     * @param ViewerableInterface $resource
     */
    public function increase(ViewerableInterface $resource, ObjectManager $manager);
}
