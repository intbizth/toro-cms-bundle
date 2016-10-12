<?php

namespace Toro\Bundle\CmsBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class RoutingRepositoryPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if ($container->hasParameter('toro.repository_by_classes') &&
            $container->hasDefinition('toro.route_provider')) {
            $repositoryByClasses = $container->getParameter('toro.repository_by_classes');
            $routeProvider = $container->getDefinition('toro.route_provider');

            foreach ($repositoryByClasses as $class => $repository) {
                $routeProvider->addMethodCall('addRepository', [$class, $repository]);
            }
        }
    }
}
