<?php

namespace Toro\Bundle\CmsBundle\Form\Type;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Toro\Bundle\MediaBundle\Form\Type\MceType;

class PostTranslationType extends AbstractResourceType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'required' => true,
                'label' => 'Title',
            ])
            ->add('slug', TextType::class, [
                'required' => true,
                'label' => 'Slug',
            ])
            ->add('description', TextareaType::class, [
                'required' => true,
                'label' => 'Description',
            ])
            ->add('body', MceType::class, [
                'required' => true,
                'label' => 'Body',
            ])
            ->add('vdoPath', TextType::class, [
                'required' => false,
                'label' => 'VDO Path',
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'toro_post_translation';
    }
}
