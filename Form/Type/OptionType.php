<?php

namespace Toro\Bundle\CmsBundle\Form\Type;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

class OptionType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('templating', TextareaType::class)
            ->add('style', TextareaType::class)
            ->add('script', TextareaType::class)
            ->add('translation', YamlType::class)
            ->add('data', YamlType::class)
        ;
    }
}
