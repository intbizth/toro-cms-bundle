<?php

namespace Toro\Bundle\CmsBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Toro\Bundle\CmsBundle\Model\Option;
use Toro\Bundle\CmsBundle\Model\OptionInterface;

class DiscriminatorEntityTypeSubscriber implements EventSubscriber
{
    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return [
            Events::loadClassMetadata,
        ];
    }

    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        /** @var ClassMetadataInfo $metadata */
        $metadata = $eventArgs->getClassMetadata();

        /** @var OptionInterface $class */
        $class = $metadata->getName();

        if ($class !== Option::class && in_array(OptionInterface::class, class_implements($class))) {
            $metadataFactory = $eventArgs->getEntityManager()->getMetadataFactory();
            /** @var ClassMetadataInfo $routeMetadata */
            $routeMetadata = $metadataFactory->getMetadataFor(Option::class);
            $routeMetadata->addDiscriminatorMapClass($class, $class);
        }
    }
}
