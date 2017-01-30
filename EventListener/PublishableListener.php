<?php

namespace Toro\Bundle\CmsBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Toro\Bundle\CmsBundle\Model\PublishableInterface;

class PublishableListener implements EventSubscriber
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
        $this->publishing($args);
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function preUpdate(LifecycleEventArgs $args)
    {
        $this->publishing($args);
    }

    /**
     * @param LifecycleEventArgs $args
     */
    private function publishing(LifecycleEventArgs $args)
    {
        $object = $args->getObject();

        if (!$object instanceof PublishableInterface) {
            return;
        }

        if (!$date = $object->getPublishedAt()) {
            return;
        }

        $object->setPublished(
            $date->getTimestamp() <= (new \DateTime())->getTimestamp()
        );
    }
}
