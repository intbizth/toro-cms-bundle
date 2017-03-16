<?php

namespace Toro\Bundle\CmsBundle\Form\Type;

use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;
use Symfony\Bridge\Doctrine\Form\DataTransformer\CollectionToArrayTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class TaxonChoiceType extends AbstractType
{
    /**
     * @var TaxonRepositoryInterface
     */
    private $taxonRepository;

    /**
     * @param TaxonRepositoryInterface $taxonRepository
     */
    public function __construct(TaxonRepositoryInterface $taxonRepository)
    {
        $this->taxonRepository = $taxonRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['multiple']) {
            $builder->addModelTransformer(new CollectionToArrayTransformer());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $rootLevel = $options['root'] ? $options['root']->getLevel() : 0;

        /** @var ChoiceView $choice */
        foreach ($view->vars['choices'] as $choice) {
            $level = $choice->data->getLevel() - $rootLevel;
            $choice->label = str_repeat('— ', $level).$choice->label;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return ChoiceType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'choices' => function (Options $options) {
                    return $this->getTaxons($options['root'] ? $options['root']->getCode() : null, $options['filter']);
                },
                'choice_value' => 'id',
                'choice_label' => 'name',
                'choice_translation_domain' => false,
                'root' => null,
                'root_code' => null,
                'filter' => null,
                'choice_attr' => function(TaxonInterface $taxon) {
                    return [
                        // data option using for selectize
                        'data-data' => json_encode([
                            'code' => $taxon->getCode(),
                            'name' => $taxon->getName(),
                            'slug' => $taxon->getSlug(),
                            'path_name' => $taxon->getPathName(),
                        ])
                    ];
                },
            ])
            ->setAllowedTypes('root', [TaxonInterface::class, 'string', 'null'])
            ->setAllowedTypes('root_code', ['string', 'null'])
            ->setAllowedTypes('filter', ['callable', 'null'])
            ->setNormalizer('root', function (Options $options, $value) {
                if (is_string($value) || $options['root_code']) {
                    return $this->taxonRepository->findOneBy(['code' => $value ?: $options['root_code']]);
                }

                return $value;
            })
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'toro_taxon_choice';
    }

    /**
     * @param string|null $rootCode
     * @param callable|null $filter
     *
     * @return TaxonInterface[]
     */
    private function getTaxons($rootCode = null, $filter = null)
    {
        $taxons = $this->taxonRepository->findNodesTreeSorted($rootCode);

        if (null !== $filter) {
            $taxons = array_filter($taxons, $filter);
        }

        return $taxons;
    }
}
