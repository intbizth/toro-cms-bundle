<?php

namespace Toro\Bundle\CmsBundle\Form\Type;

use Sylius\Bundle\ChannelBundle\Form\Type\ChannelChoiceType;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Bundle\ResourceBundle\Form\Type\ResourceTranslationsType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Toro\Bundle\CmsBundle\Model\PostInterface;
use Toro\Bundle\MediaBundle\Form\Type\ImageType;

class PostType extends AbstractResourceType
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
            ->add('type', ChoiceType::class, [
                'required' => true,
                'label' => 'Type',
                'choices' => array_flip([
                    PostInterface::TYPE_CONTENT => 'Content',
                    PostInterface::TYPE_YOUTUBE => 'Youtube',
                ])
            ])
            ->add('publishedAt', DateTimeType::class, array(
                'required' => false,
                'widget' => 'single_text',
                'format' => DateTimeType::HTML5_FORMAT,
                'label' => 'Published At',
            ))
            ->add('published', CheckboxType::class, [
                'required' => false,
                'label' => 'Published'
            ])
            ->add('options', PostOptionType::class)
            ->add('translations', ResourceTranslationsType::class, [
                'entry_type' => PostTranslationType::class
            ])
            ->add('cover', ImageType::class, [
                'required' => false,
                'label' => 'Cover',
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'toro_post';
    }
}
