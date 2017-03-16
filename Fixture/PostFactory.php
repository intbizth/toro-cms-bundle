<?php

namespace Toro\Bundle\CmsBundle\Fixture;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ODM\PHPCR\DocumentManagerInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Cmf\Bundle\MediaBundle\Doctrine\Phpcr\Directory;
use Symfony\Cmf\Bundle\MediaBundle\Doctrine\Phpcr\Image;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Toro\Bundle\CmsBundle\Model\PostInterface;
use Toro\Bundle\FixtureBundle\DataFixture\Factory\AbstractLocaleAwareFactory;
use Toro\Bundle\FixtureBundle\DataFixture\Uploader\ImageUploadHelper;

final class PostFactory extends AbstractLocaleAwareFactory
{
    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @var RepositoryInterface
     */
    private $repository;

    /**
     * @var RepositoryInterface
     */
    private $faker;

    /**
     * @var OptionsResolver
     */
    private $optionsResolver;

    /**
     * @var ImageUploadHelper
     */
    private $uploadFileHelper;

    /**
     * @var DocumentManagerInterface
     */
    private $documentManager;

    /**
     * @var string
     */
    private $rootFolder;

    /**
     * @param FactoryInterface $factory
     * @param RepositoryInterface $repository
     */
    public function __construct(
        FactoryInterface $factory,
        RepositoryInterface $repository,
        ImageUploadHelper $uploadFileHelper,
        ManagerRegistry $registry,
        $documentManagerName,
        $rootFolder
    ) {
        $this->factory = $factory;
        $this->repository = $repository;
        $this->uploadFileHelper = $uploadFileHelper;
        $this->faker = \Faker\Factory::create();
        $this->documentManager = $registry->getManager($documentManagerName);
        $this->rootFolder = $rootFolder;

        $this->checkAndCreateFolder();

        $this->optionsResolver =
            (new OptionsResolver())
                ->setDefault('images', function (Options $options) {
                    return [];
                })
                ->setDefault('title', function (Options $options) {
                    return $this->generateFakerWithLocale('text', 80);
                })
                ->setDefault('slug', function (Options $options) {
                    return $this->generateFakerWithLocale('slug', 3);
                })
                ->setDefault('description', function (Options $options) {
                    return $this->generateFakerWithLocale('sentence');
                })
                ->setDefault('body', function (Options $options) {
                    return $this->generateFakerWithLocale('paragraph');
                })
                ->setDefault('type', function (Options $options) {
                    $types = [PostInterface::TYPE_CONTENT, PostInterface::TYPE_YOUTUBE];

                    return $types[array_rand($types, 1)];
                })

                ->setRequired(['type', 'title', 'slug', 'description', 'body'])
        ;
    }

    private function generateFakerWithLocale($fakeName, $length = null)
    {
        $values = [];

        foreach ($this->getLocales() as $locale) {
            $values[$locale] = $this->faker->$fakeName($length);
        }

        return $values;
    }

    private function checkAndCreateFolder()
    {
        if ($this->documentManager->find(null, $dirname = $this->rootFolder.'/pages')) {
            return;
        }

        $dir = new Directory();
        $dir->setName('pages');
        $dir->setId($dirname);

        $this->documentManager->persist($dir);
        $this->documentManager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function create($key, array $options = [])
    {
        $options = $this->optionsResolver->resolve($options);

        /** @var PostInterface $object */
        $object = $this->factory->createNew();

        $this->setLocalizedData($object, [
            'title',
            'slug',
            'description',
            'body',
        ], $options);

        return $object;
    }
}
