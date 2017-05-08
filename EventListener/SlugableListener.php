<?php

namespace Toro\Bundle\CmsBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Toro\Bundle\CmsBundle\Model\SlugAwareInterface;
use Toro\Bundle\CmsBundle\Slug\URLify;

class SlugableListener implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return array(
            'prePersist',
            'preUpdate',
        );
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $this->assignSlug($args);
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function preUpdate(LifecycleEventArgs $args)
    {
        $this->assignSlug($args);
    }

    /**
     * @param LifecycleEventArgs $args
     */
    private function assignSlug(LifecycleEventArgs $args)
    {
        $object = $args->getObject();

        if (!$object instanceof SlugAwareInterface) {
            return;
        }

        if (!$object->getSlug() && $object->getSlugable()) {
            $object->setSlug(URLify::slug($object->getSlugable()));
        }
    }
}
