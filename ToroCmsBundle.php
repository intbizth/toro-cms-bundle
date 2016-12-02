<?php

namespace Toro\Bundle\CmsBundle;

use Sylius\Bundle\ResourceBundle\AbstractResourceBundle;
use Sylius\Bundle\ResourceBundle\SyliusResourceBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Toro\Bundle\CmsBundle\DependencyInjection\Compiler\RegisterLocaleHandlersPass;
use Toro\Bundle\CmsBundle\DependencyInjection\Compiler\RoutingRepositoryPass;
use Toro\Bundle\CmsBundle\DependencyInjection\Compiler\TranslatableEntityLocalePass;

class ToroCmsBundle extends AbstractResourceBundle
{
    const APPLICATION_NAME = 'toro';

    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $builder)
    {
        parent::build($builder);

        $builder->addCompilerPass(new RoutingRepositoryPass());
        $builder->addCompilerPass(new RegisterLocaleHandlersPass());
        $builder->addCompilerPass(new TranslatableEntityLocalePass());
    }

    /**
     * {@inheritdoc}
     */
    public function getSupportedDrivers()
    {
        return [
            SyliusResourceBundle::DRIVER_DOCTRINE_ORM,
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getModelNamespace()
    {
        return 'Toro\Bundle\CmsBundle\Model';
    }
}
