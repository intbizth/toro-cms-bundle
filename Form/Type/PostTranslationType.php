<?php

namespace Toro\Bundle\CmsBundle\Form\Type;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Toro\Bundle\CmsBundle\Model\PostInterface;
use Toro\Bundle\CmsBundle\Model\PostTranslationInterface;
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
            ->add('vdoPath', UrlType::class, [
                'required' => false,
                'label' => 'VDO Path',
            ])
            ->add('description', TextareaType::class, [
                'required' => true,
                'label' => 'Description',
            ])
            ->add('body', MceType::class, [
                'required' => true,
                'label' => 'Body',
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'validation_groups' => function (FormInterface $form) {
                $groups = (array) $this->validationGroups;
                $data = $form->getData();
                $postType = null;

                if ($data instanceof PostTranslationInterface) {
                    $postType = strtolower($data->getTranslatable()->getType());
                }

                if (PostInterface::TYPE_YOUTUBE === $postType) {
                    $groups[] = 'toro_vdo';
                }

                return $groups;
            },
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'toro_post_translation';
    }
}
