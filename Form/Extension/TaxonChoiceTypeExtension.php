<?php

namespace Toro\Bundle\CmsBundle\Form\Extension;

use Sylius\Bundle\TaxonomyBundle\Form\Type\TaxonChoiceType;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractTypeExtension;

class TaxonChoiceTypeExtension extends AbstractTypeExtension
{
    /**
     * @var TaxonRepositoryInterface
     */
    private $taxonRepository;

    public function __construct(RepositoryInterface $repository)
    {
        $this->taxonRepository = $repository;
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $rootLevel = $options['root'] ? $options['root']->getLevel() : 0;

        /** @var ChoiceView $choice */
        foreach ($view->vars['choices'] as $choice) {
            $level = ($choice->data->getLevel() - $rootLevel) - 1;
            $choice->label = str_replace('— ', '', $choice->label);

            if ($level > 0) {
                $choice->label = str_repeat('— ', $level).$choice->label;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'choices' => function (Options $options) {
                    if (null !== $options['root']) {
                        $taxons = $this->taxonRepository->findChildren($options['root']->getCode());
                    } else {
                        $taxons = $this->taxonRepository->findNodesTreeSorted();
                    }

                    if (null !== $options['filter']) {
                        $taxons = array_filter($taxons, $options['filter']);
                    }

                    return $taxons;
                },
                'choice_value' => 'id',
                'choice_label' => 'name',
                'choice_translation_domain' => false,
                'root' => null,
                'filter' => null,
            ])
            ->setAllowedTypes('root', [TaxonInterface::class, 'string', 'null'])
            ->setAllowedTypes('filter', ['callable', 'null'])
            ->setNormalizer('root', function (Options $options, $value) {
                if (is_string($value)) {
                    return $this->taxonRepository->findOneBy(['code' => $value]);
                }

                return $value;
            })
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return TaxonChoiceType::class;
    }
}
