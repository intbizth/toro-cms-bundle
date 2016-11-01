<?php

namespace Toro\Bundle\CmsBundle\EventListener;

use Symfony\Component\EventDispatcher\GenericEvent;
use Toro\Bundle\CmsBundle\Provider\ResourceViewerProviderInterface;

class ResourceViewerListener
{
    /**
     * @var ResourceViewerProviderInterface
     */
    private $provider;

    public function __construct(ResourceViewerProviderInterface $provider)
    {
        $this->provider = $provider;
    }

    /**
     * @param GenericEvent $event
     */
    public function increase(GenericEvent $event)
    {
        $this->provider->increase($event->getSubject());
    }
}
