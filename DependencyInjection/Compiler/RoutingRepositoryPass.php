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
        if ($container->hasParameter('toro.options.matcher_class')) {
            $container
                ->setParameter('router.options.matcher_class', $container->getParameter('toro.options.matcher_class'))
            ;
        }

        if ($container->hasParameter('toro.options.matcher_base_class')) {
            $container
                ->setParameter('router.options.matcher_base_class', $container->getParameter('toro.options.matcher_base_class'))
            ;
        }
    }
}
