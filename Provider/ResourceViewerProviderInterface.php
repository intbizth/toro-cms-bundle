<?php

namespace Toro\Bundle\CmsBundle\Provider;

use Doctrine\Common\Persistence\ObjectManager;
use Toro\Bundle\CmsBundle\Model\ViewerableInterface;

interface ResourceViewerProviderInterface
{
    /**
     * @param ViewerableInterface $resource
     * @param ObjectManager $manager
     */
    public function increase(ViewerableInterface $resource, ObjectManager $manager);

    /**
     * Flug viewer log
     */
    public function flushViewerLog();
}
