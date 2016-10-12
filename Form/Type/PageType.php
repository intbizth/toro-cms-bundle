<?php

namespace Toro\Bundle\CmsBundle\Form\Type;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Bundle\ResourceBundle\Form\Type\ResourceTranslationsType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;

class PageType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('channel', 'sylius_channel_choice', [
                'required' => true,
                'label' => 'Channel',
            ])
            ->add('published', CheckboxType::class, [
                'required' => false,
                'label' => 'Published'
            ])
            ->add('partial', CheckboxType::class, [
                'required' => false,
                'label' => 'Partial'
            ])
            ->add('options', PageOptionType::class)
            ->add('translations', ResourceTranslationsType::class, [
                'type' => PageTranslationType::class
            ])
        ;
    }

    public function getName()
    {
        return 'toro_page';
    }
}
