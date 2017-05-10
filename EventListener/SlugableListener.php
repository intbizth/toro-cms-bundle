<?php

namespace Toro\Bundle\CmsBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Sylius\Component\Resource\Generator\RandomnessGenerator;
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
            $slugable = trim(preg_replace('/\s+/', ' ', $object->getSlugable()))
                ?: (new RandomnessGenerator())->generateUriSafeString(60);

            $object->setSlug(URLify::slug($slugable));
        }
    }
}
