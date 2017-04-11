<?php

namespace Toro\Bundle\CmsBundle\EventListener;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Sylius\Component\Resource\Generator\RandomnessGeneratorInterface;
use Toro\Bundle\CmsBundle\Model\UniqueTokenAwareInterface;

class UniqueTokenAssignerListener
{
    /**
     * @var RandomnessGeneratorInterface
     */
    private $tokenGenerator;

    public function __construct(RandomnessGeneratorInterface $tokenGenerator)
    {
        $this->tokenGenerator = $tokenGenerator;
    }

    /**
     * @param LifecycleEventArgs $event
     */
    public function prePersist(LifecycleEventArgs $event)
    {
        $object = $event->getObject();

        if (!$object instanceof UniqueTokenAwareInterface) {
            return;
        }

        if ($object->getUniqueToken()) {
            return;
        }

        $object->setUniqueToken($this->tokenGenerator->generateUriSafeString(20));
    }
}
