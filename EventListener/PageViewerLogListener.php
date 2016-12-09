<?php

namespace Toro\Bundle\CmsBundle\EventListener;

use Symfony\Component\EventDispatcher\GenericEvent;
use Toro\Bundle\CmsBundle\Provider\ResourceViewerProviderInterface;

class PageViewerLogListener
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
    public function insertLog(GenericEvent $event)
    {
        // increase viewer
        $this->provider->increase(
            $event->getSubject(), $event->getArgument('manager')
        );
    }

    public function flushLog()
    {
        $this->provider->flushViewerLog();
    }
}
