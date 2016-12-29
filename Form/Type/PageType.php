<?php

namespace Toro\Bundle\CmsBundle\Form\Type;

use Sylius\Bundle\ChannelBundle\Form\Type\ChannelChoiceType;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Bundle\ResourceBundle\Form\Type\ResourceTranslationsType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;

class PageType extends AbstractResourceType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('channel', ChannelChoiceType::class, [
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
            ->add('deletable', CheckboxType::class, [
                'required' => false,
                'label' => 'Deletable'
            ])
            ->add('options', PageOptionType::class)
            ->add('translations', ResourceTranslationsType::class, [
                'entry_type' => PageTranslationType::class
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'toro_page';
    }
}
