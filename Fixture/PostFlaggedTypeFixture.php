<?php

namespace Toro\Bundle\CmsBundle\Fixture;

use Doctrine\Common\Persistence\ObjectManager;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Toro\Bundle\CoreBundle\Model\PostFlaggedTypeInterface;
use Toro\Bundle\FixtureBundle\DataFixture\AbstractResourceFixture;

class PostFlaggedTypeFixture extends AbstractResourceFixture
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'post_flagged_type';
    }

    /**
     * {@inheritdoc}
     */
    protected function configureResourceNode(ArrayNodeDefinition $resourceNode)
    {
        $resourceNode
            ->children()
                ->scalarNode('code')->cannotBeEmpty()->end()
                ->scalarNode('name')->cannotBeEmpty()->end()
                ->booleanNode('enabled')->defaultTrue()->end()
                ->booleanNode('singleActive')->defaultFalse()->end()
                ->arrayNode('config')
                    ->prototype('scalar')->end()
                ->end()
        ;
    }
}
