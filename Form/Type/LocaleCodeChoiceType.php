<?php

namespace Toro\Bundle\CmsBundle\Form\Type;

use Sylius\Component\Locale\Model\LocaleInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocaleCodeChoiceType extends AbstractType
{
    /**
     * @var RepositoryInterface
     */
    protected $localeRepository;

    /**
     * @param RepositoryInterface $localeRepository
     */
    public function __construct(RepositoryInterface $localeRepository)
    {
        $this->localeRepository = $localeRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'choices' => $this->getLocalesCodes(),
                'choices_as_values' => true,
                'placeholder' => null,
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'choice';
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'sylius_locale_code_choice';
    }

    /**
     * @return array
     */
    private function getLocalesCodes()
    {
        $localesCodes = [];

        /** @var LocaleInterface $locale */
        foreach ($this->localeRepository->findAll() as $locale) {
            $localesCodes[$locale->getName()] = $locale->getCode();
        }

        return $localesCodes;
    }
}
