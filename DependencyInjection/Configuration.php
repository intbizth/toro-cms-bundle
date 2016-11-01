<?php

namespace Toro\Bundle\CmsBundle\DependencyInjection;

use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Bundle\ResourceBundle\SyliusResourceBundle;
use Sylius\Component\Resource\Factory\Factory;
use Sylius\Component\Resource\Factory\TranslatableFactory;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Toro\Bundle\CmsBundle\Controller\PageController;
use Toro\Bundle\CmsBundle\Doctrine\ORM\PageRepository;
use Toro\Bundle\CmsBundle\Form\Type\PageOptionType;
use Toro\Bundle\CmsBundle\Form\Type\PageTranslationType;
use Toro\Bundle\CmsBundle\Form\Type\PageType;
use Toro\Bundle\CmsBundle\Model\Option;
use Toro\Bundle\CmsBundle\Model\OptionInterface;
use Toro\Bundle\CmsBundle\Model\Page;
use Toro\Bundle\CmsBundle\Model\PageInterface;
use Toro\Bundle\CmsBundle\Model\PageOption;
use Toro\Bundle\CmsBundle\Model\PageOptionInterface;
use Toro\Bundle\CmsBundle\Model\PageTranslation;
use Toro\Bundle\CmsBundle\Model\PageTranslationInterface;
use Toro\Bundle\CmsBundle\Model\ResourceViewer;
use Toro\Bundle\CmsBundle\Model\ResourceViewerInterface;
use Toro\Bundle\CmsBundle\ToroCmsBundle;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('toro_cms');

        $rootNode
            ->children()
                ->scalarNode('driver')->defaultValue(SyliusResourceBundle::DRIVER_DOCTRINE_ORM)->end()
                ->scalarNode('fixture')->defaultFalse()->end()
            ->end()
        ;

        $this->addResourcesSection($rootNode);

        return $treeBuilder;
    }

    /**
     * @param ArrayNodeDefinition $node
     */
    private function addResourcesSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('resources')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('page')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode('options')->end()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->defaultValue(Page::class)->cannotBeEmpty()->end()
                                        ->scalarNode('interface')->defaultValue(PageInterface::class)->cannotBeEmpty()->end()
                                        ->scalarNode('controller')->defaultValue(PageController::class)->cannotBeEmpty()->end()
                                        ->scalarNode('repository')->defaultValue(PageRepository::class)->cannotBeEmpty()->end()
                                        ->scalarNode('factory')->defaultValue(TranslatableFactory::class)->end()
                                        ->arrayNode('form')
                                            ->addDefaultsIfNotSet()
                                            ->children()
                                                ->scalarNode('default')->defaultValue(PageType::class)->cannotBeEmpty()->end()
                                                //->scalarNode('from_identifier')->defaultValue(ResourceFromIdentifierType::class)->cannotBeEmpty()->end()
                                            ->end()
                                        ->end()
                                    ->end()
                                ->end()
                                ->arrayNode('validation_groups')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->arrayNode('default')
                                            ->prototype('scalar')->end()
                                            ->defaultValue([ToroCmsBundle::APPLICATION_NAME])
                                        ->end()
                                        ->arrayNode('from_identifier')
                                            ->prototype('scalar')->end()
                                            ->defaultValue([ToroCmsBundle::APPLICATION_NAME])
                                        ->end()
                                    ->end()
                                ->end()
                                ->arrayNode('translation')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->variableNode('options')->end()
                                        ->arrayNode('classes')
                                            ->addDefaultsIfNotSet()
                                            ->children()
                                                ->scalarNode('model')->defaultValue(PageTranslation::class)->cannotBeEmpty()->end()
                                                ->scalarNode('interface')->defaultValue(PageTranslationInterface::class)->cannotBeEmpty()->end()
                                                ->scalarNode('controller')->defaultValue(ResourceController::class)->cannotBeEmpty()->end()
                                                ->scalarNode('repository')->cannotBeEmpty()->end()
                                                ->scalarNode('factory')->defaultValue(Factory::class)->end()
                                                ->arrayNode('form')
                                                    ->addDefaultsIfNotSet()
                                                    ->children()
                                                        ->scalarNode('default')->defaultValue(PageTranslationType::class)->cannotBeEmpty()->end()
                                                    ->end()
                                                ->end()
                                            ->end()
                                        ->end()
                                        ->arrayNode('validation_groups')
                                            ->addDefaultsIfNotSet()
                                            ->children()
                                                ->arrayNode('default')
                                                    ->prototype('scalar')->end()
                                                    ->defaultValue([ToroCmsBundle::APPLICATION_NAME])
                                                ->end()
                                            ->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('option')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode('options')->end()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->defaultValue(Option::class)->cannotBeEmpty()->end()
                                        ->scalarNode('interface')->defaultValue(OptionInterface::class)->cannotBeEmpty()->end()
                                        ->scalarNode('controller')->defaultValue(ResourceController::class)->cannotBeEmpty()->end()
                                        ->scalarNode('repository')->defaultValue(EntityRepository::class)->cannotBeEmpty()->end()
                                        ->scalarNode('factory')->defaultValue(Factory::class)->end()
                                        ->arrayNode('form')
                                            ->addDefaultsIfNotSet()
                                            ->children()
                                                //->scalarNode('default')->defaultValue(NewsType::class)->cannotBeEmpty()->end()
                                                //->scalarNode('from_identifier')->defaultValue(ResourceFromIdentifierType::class)->cannotBeEmpty()->end()
                                            ->end()
                                        ->end()
                                    ->end()
                                ->end()
                                ->arrayNode('validation_groups')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->arrayNode('default')
                                            ->prototype('scalar')->end()
                                            ->defaultValue([ToroCmsBundle::APPLICATION_NAME])
                                        ->end()
                                        ->arrayNode('from_identifier')
                                            ->prototype('scalar')->end()
                                            ->defaultValue([ToroCmsBundle::APPLICATION_NAME])
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('page_option')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode('options')->end()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->defaultValue(PageOption::class)->cannotBeEmpty()->end()
                                        ->scalarNode('interface')->defaultValue(PageOptionInterface::class)->cannotBeEmpty()->end()
                                        ->scalarNode('controller')->defaultValue(ResourceController::class)->cannotBeEmpty()->end()
                                        ->scalarNode('repository')->defaultValue(EntityRepository::class)->cannotBeEmpty()->end()
                                        ->scalarNode('factory')->defaultValue(Factory::class)->end()
                                        ->arrayNode('form')
                                            ->addDefaultsIfNotSet()
                                            ->children()
                                                ->scalarNode('default')->defaultValue(PageOptionType::class)->cannotBeEmpty()->end()
                                                //->scalarNode('from_identifier')->defaultValue(ResourceFromIdentifierType::class)->cannotBeEmpty()->end()
                                            ->end()
                                        ->end()
                                    ->end()
                                ->end()
                                ->arrayNode('validation_groups')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->arrayNode('default')
                                            ->prototype('scalar')->end()
                                            ->defaultValue([ToroCmsBundle::APPLICATION_NAME])
                                        ->end()
                                        ->arrayNode('from_identifier')
                                            ->prototype('scalar')->end()
                                            ->defaultValue([ToroCmsBundle::APPLICATION_NAME])
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('resource_viewer')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->defaultValue(ResourceViewer::class)->cannotBeEmpty()->end()
                                        ->scalarNode('interface')->defaultValue(ResourceViewerInterface::class)->cannotBeEmpty()->end()
                                        ->scalarNode('controller')->defaultValue(ResourceController::class)->cannotBeEmpty()->end()
                                        ->scalarNode('repository')->defaultValue(EntityRepository::class)->cannotBeEmpty()->end()
                                        ->scalarNode('factory')->defaultValue(Factory::class)->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
