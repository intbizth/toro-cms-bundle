<?php

namespace Toro\Bundle\CmsBundle\Form\Type;

use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;
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
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $rootLevel = 0;

        /** @var ChoiceView $choice */
        foreach ($view->vars['choices'] as $i => $choice) {
            $dash = '— ';

            if (0 === $i) {
                $rootLevel = $choice->data->getLevel();
            }

            if (preg_match("/^$dash/", $choice->label)) {
                continue;
            }

            $level = $choice->data->getLevel() - $rootLevel;
            $choice->label = @str_repeat($dash, $level).$choice->label;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return EntityType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'choices' => function (Options $options) {
                    return $this->getTaxons($options['root'], $options['filter']);
                },
                'choice_value' => 'id',
                'choice_label' => 'name',
                'choice_translation_domain' => false,
                'root' => null,
                'root_code' => null,
                'filter' => null,
                'class' => $this->taxonRepository->getClassName(),
                'choice_attr' => function(TaxonInterface $taxon) {
                    return [
                        // data option using for selectize
                        'data-parent-id' => $taxon->getParent() ? $taxon->getParent()->getId() : null,
                        'data-data' => json_encode([
                            'id' => $taxon->getId(),
                            'code' => $taxon->getCode(),
                            'name' => $taxon->getName(),
                            'slug' => $taxon->getSlug(),
                            'level' => $taxon->getLevel(),
                            'path_name' => $taxon->getPathName(),
                        ])
                    ];
                },
            ])
            ->setAllowedTypes('root', [TaxonInterface::class, 'string', 'null'])
            ->setAllowedTypes('root_code', ['string', 'null'])
            ->setAllowedTypes('filter', ['callable', 'null'])
            ->setNormalizer('root', function (Options $options, $value) {
                if ($value instanceof TaxonInterface) {
                    return $value->getCode();
                }

                return $value ?: $options['root_code'];;
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
