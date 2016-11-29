<?php

namespace Toro\Bundle\CmsBundle\Form\Extension;

use Sylius\Bundle\ChannelBundle\Form\Type\ChannelType;
use Sylius\Bundle\LocaleBundle\Form\Type\LocaleChoiceType;
use Sylius\Bundle\ThemeBundle\Form\Type\ThemeNameChoiceType;
use Sylius\Bundle\TaxonomyBundle\Form\Type\TaxonCodeChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractTypeExtension;

class ChannelTypeExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('locales', LocaleChoiceType::class, [
                'label' => 'Locales',
                'multiple' => true,
            ])
            ->add('defaultLocale', LocaleChoiceType::class, [
                'label' => 'Default locale',
                'placeholder' => null,
            ])
            ->add('themeName', ThemeNameChoiceType::class, [
                'label' => 'Theme',
                'required' => false,
                'empty_data' => null,
                'placeholder' => 'Please select theme',
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return ChannelType::class;
    }
}
