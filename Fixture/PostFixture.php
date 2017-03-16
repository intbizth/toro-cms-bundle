<?php

namespace Toro\Bundle\CmsBundle\Fixture;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Toro\Bundle\FixtureBundle\DataFixture\AbstractResourceFixture;

final class PostFixture extends AbstractResourceFixture
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'post';
    }

    /**
     * {@inheritdoc}
     */
    protected function configureResourceNode(ArrayNodeDefinition $resourceNode)
    {
        $resourceNode
            ->children()
                ->arrayNode('taxon')
                    ->prototype('scalar')->end()->end()
                ->arrayNode('type')
                    ->prototype('scalar')->end()->end()
                ->arrayNode('title')
                    ->useAttributeAsKey('locale')
                    ->prototype('scalar')->end()->end()
                ->arrayNode('slug')
                    ->useAttributeAsKey('locale')
                    ->prototype('scalar')->end()->end()
                ->arrayNode('description')
                    ->useAttributeAsKey('locale')
                    ->prototype('scalar')->end()->end()
                ->arrayNode('body')
                    ->useAttributeAsKey('locale')
                    ->prototype('scalar')->end()->end()
                ->arrayNode('vdo_path')
                    ->useAttributeAsKey('locale')
                    ->prototype('scalar')->end()->end()
                ->arrayNode('cover')
                    ->prototype('scalar')->end()->end()
                ->arrayNode('published')
                    ->prototype('scalar')->end()->end()
                ->arrayNode('published_at')
                    ->prototype('scalar')->end()->end()
                ->arrayNode('created_at')
                    ->prototype('scalar')->end()->end()
                ->arrayNode('created_by')
                    ->prototype('scalar')->end()->end()
                ->arrayNode('images')
                    ->prototype('scalar')->end()->end()
        ;
    }
}
