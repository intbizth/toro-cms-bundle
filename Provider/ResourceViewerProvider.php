<?php

namespace Toro\Bundle\CmsBundle\Provider;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\User\Model\UserInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
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
     * FIXME: http://symfony.com/doc/current/request/load_balancer_reverse_proxy.html -- not work
     * FIXME: https://github.com/symfony/symfony/issues/20178
     * @param Request $request
     */
    private function getProxyIp(Request $request)
    {
        $ips = explode(',', preg_replace('/\s+/', '', $request->headers->get('x-forwarded-for')));

        if (!empty($ips)) {
            return $ips[0];
        }

        return;
    }

    /**
     * {@inheritdoc}
     */
    public function increase(ViewerableInterface $resource, ObjectManager $manager)
    {
        if (!$resource->isViewerEnabled()) {
            return;
        }

        if (!$request = $this->requestStack->getCurrentRequest()) {
            return;
        }

        $ip = $this->getProxyIp($request) ?: $request->getClientIp();
        $class = get_class($resource);

        /** @var ResourceViewerInterface $rv */
        $rv = $this->factory->createNew();

        if ($resource->isViewerLogEnabled()) {
            $rv->setResourceName($class);
            $rv->setIp($ip);
            $rv->setResourceId($resource->getId());
            $rv->setMeta($request->headers->all());

            if ($this->tokenStorage && $this->tokenStorage->getToken()) {
                $user = $this->tokenStorage->getToken()->getUser();

                if ($user instanceof UserInterface) {
                    $rv->setUser($user);
                }
            }

            $this->manager->persist($rv);
        }

        $lastLog = $resource->getLastViewerStampTime();

        if ($lastLog && ($lastLog->getTimestamp() > strtotime('-1 day'))) {
            return;
        }

        // viewer ++
        $resource->increaseViewer();

        $table = $manager->getClassMetadata($class)->getTableName();
        $manager->getConnection()->update($table, ['viewers' => $resource->getViewers()], ['id' => $resource->getId()]);
    }

    /**
     * {@inheritdoc}
     */
    public function flushViewerLog()
    {
        $this->manager->flush();
    }
}
