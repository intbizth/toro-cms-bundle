<?php

namespace Toro\Bundle\CmsBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Toro\Bundle\CmsBundle\Locale\TranslatableEntityLocaleAssigner;

final class TranslatableEntityLocalePass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $translatableEntityLocaleAssignerDefinition = new Definition(TranslatableEntityLocaleAssigner::class);
        $translatableEntityLocaleAssignerDefinition->addArgument(new Reference('sylius.context.locale'));
        $translatableEntityLocaleAssignerDefinition->addArgument(new Reference('sylius.translation_locale_provider'));

        $container->setDefinition('sylius.translatable_entity_locale_assigner', $translatableEntityLocaleAssignerDefinition);
    }
}
