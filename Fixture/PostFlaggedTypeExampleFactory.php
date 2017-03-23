<?php

namespace Toro\Bundle\CmsBundle\Fixture;

use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Toro\Bundle\CoreBundle\Model\PostFlaggedTypeInterface;
use Toro\Bundle\FixtureBundle\DataFixture\Factory\ExampleFactoryInterface;
use Toro\Bundle\FixtureBundle\StringInflector;

class PostFlaggedTypeExampleFactory implements ExampleFactoryInterface
{
    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @var \Faker\Generator
     */
    private $faker;

    /**
     * @var OptionsResolver
     */
    private $optionsResolver;

    /**
     * @param FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;

        $this->faker = \Faker\Factory::create();
        $this->optionsResolver = new OptionsResolver();

        $this->configureOptions($this->optionsResolver);
    }

    /**
     * {@inheritdoc}
     */
    public function create($key, array $options = [])
    {
        $options = $this->optionsResolver->resolve($options);

        /** @var PostFlaggedTypeInterface $object */
        $object = $this->factory->createNew();

        $object->setCode($options['code']);
        $object->setName($options['name']);
        $object->setConfig($options['config']);
        $object->setEnabled($options['enabled']);
        $object->setSingleActive($options['singleActive']);

        return $object;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('name', function (Options $options) {
                return $this->faker->words(3, true);
            })
            ->setDefault('code', function (Options $options) {
                return StringInflector::nameToCode($options['name']);
            })
            ->setDefault('config', function (Options $options) {
                return [];
            })
            ->setDefault('enabled', function (Options $options) {
                return true;
            })
            ->setDefault('singleActive', function (Options $options) {
                return false;
            })
        ;
    }
}
