<?php

namespace Toro\Bundle\CmsBundle\DependencyInjection;

use Sylius\Bundle\ResourceBundle\DependencyInjection\Extension\AbstractResourceExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader;
use Toro\Bundle\CmsBundle\ToroCmsBundle;

class ToroCmsExtension extends AbstractResourceExtension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $loader->load('services.xml');

        if ($config['fixture']) {
            $loader->load('fixtures.xml');
        }

        if ($config['locale_enabled']) {
            $loader->load('locales.xml');
        }

        if ($config['form_extension']['channel']) {
            $loader->load('forms/channel-extension.xml');
        }

        $this->registerResources(ToroCmsBundle::APPLICATION_NAME, $config['driver'], $config['resources'], $container);
    }
}
