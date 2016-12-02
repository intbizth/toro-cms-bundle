<?php

namespace Toro\Bundle\CmsBundle\Form\Extension;

use Sylius\Bundle\TaxonomyBundle\Form\Type\TaxonType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Toro\Bundle\CmsBundle\Form\Type\YamlType;

class TaxonTypeExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('options', YamlType::class, [])
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
                $data = $event->getData();

                if (empty($data['translations'])) {
                    return;
                }

                foreach ($data['translations'] as &$_data) {
                    if (empty($_data['slug']) && !empty($_data['name'])) {
                        $_data['slug'] = uniqid();
                    }
                }

                $event->setData($data);
            })
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return TaxonType::class;
    }
}
