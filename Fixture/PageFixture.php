<?php

namespace Toro\Bundle\CmsBundle\Fixture;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Toro\Bundle\FixtureBundle\DataFixture\AbstractResourceFixture;

final class PageFixture extends AbstractResourceFixture
{
    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'page';
    }

    /**
     * {@inheritdoc}
     */
    protected function configureResourceNode(ArrayNodeDefinition $resourceNode)
    {
        $resourceNode
            ->children()
                ->arrayNode('title')
                    ->useAttributeAsKey('locale')
                    ->prototype('scalar')->end()->end()
                ->arrayNode('slug')
                    ->useAttributeAsKey('locale')
                    ->prototype('scalar')->end()->end()
                ->arrayNode('body')
                    ->useAttributeAsKey('locale')
                    ->prototype('scalar')->end()->end()
                ->arrayNode('images')
                    ->prototype('scalar')->end()->end()
        ;
    }
}
