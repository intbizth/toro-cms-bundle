<?php

namespace Toro\Bundle\CmsBundle\Provider;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\User\Model\UserInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Toro\Bundle\CmsBundle\Model\ResourceViewerInterface;
use Toro\Bundle\CmsBundle\Model\ViewerableInterface;

class ResourceViewerProvider implements ResourceViewerProviderInterface
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var ObjectRepository
     */
    private $repository;

    /**
     * @var ObjectManager
     */
    private $manager;

    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @var TokenStorageInterface;
     */
    private $tokenStorage;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    public function __construct(
        TokenStorageInterface $tokenStorage,
        EventDispatcherInterface $eventDispatcher,
        ObjectManager $manager,
        ObjectRepository $repository,
        FactoryInterface $factory,
        RequestStack $requestStack = null
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->eventDispatcher = $eventDispatcher;
        $this->manager = $manager;
        $this->repository = $repository;
        $this->factory = $factory;
        $this->requestStack = $requestStack;
    }

    /**
     * {@inheritdoc}
     */
    public function increase(ViewerableInterface $resource, ObjectManager $manager)
    {
        if (!$request = $this->requestStack->getCurrentRequest()) {
            return;
        }

        // viewer ++
        $resource->increaseViewer();

        /** @var ResourceViewerInterface $rv */
        $rv = $this->factory->createNew();
        $rv->setResourceName(get_class($resource));
        $rv->setResourceId($resource->getId());
        $rv->setIp($request->getClientIp());
        $rv->setMeta($request->headers->all());

        if ($this->tokenStorage && $this->tokenStorage->getToken()) {
            $user = $this->tokenStorage->getToken()->getUser();

            if ($user instanceof UserInterface) {
                $rv->setUser($user);
            }
        }

        $this->manager->persist($rv);
        $this->manager->flush($rv);

        $table = $manager->getClassMetadata(get_class($resource))->getTableName();

        $manager->getConnection()->exec(
            sprintf('UPDATE %s SET viewers = %s WHERE id = %s', $table, $resource->getViewers(), $resource->getId())
        );
    }
}
