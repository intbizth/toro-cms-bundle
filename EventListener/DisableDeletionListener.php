<?php

namespace Toro\Bundle\CmsBundle\EventListener;

use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Resource\Exception\UnexpectedTypeException;
use Toro\Bundle\CmsBundle\Model\DisableDeletionInterface;

class DisableDeletionListener
{
    /**
     * @param ResourceControllerEvent $event
     */
    public function onResourcePreDelete(ResourceControllerEvent $event)
    {
        $object = $event->getSubject();

        if (!$object instanceof DisableDeletionInterface) {
            throw new UnexpectedTypeException($object, DisableDeletionInterface::class);
        }

        if (!$object->isDeletable()) {
            $event->stop('toro.ui.cant_delete_this_resource');
        }
    }
}
