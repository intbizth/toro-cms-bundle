<?php

namespace Toro\Bundle\CmsBundle\EventListener;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Toro\Bundle\CmsBundle\Model\UniqueTokenAwareInterface;
use Toro\Bundle\CmsBundle\TokenAssigner\UniqueTokenGenerator;

class UniqueTokenAssignerListener
{
    public function prePersist(LifecycleEventArgs $event)
    {
        $object = $event->getObject();

        if (!$object instanceof UniqueTokenAwareInterface) {
            return;
        }

        $object->setUniqueToken(
            (new UniqueTokenGenerator())->generate(10)
        );
    }
}
