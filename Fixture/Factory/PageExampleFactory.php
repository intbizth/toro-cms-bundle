<?php

namespace Toro\Bundle\CmsBundle\Fixture\Factory;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ODM\PHPCR\DocumentManagerInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Cmf\Bundle\MediaBundle\Doctrine\Phpcr\Directory;
use Symfony\Cmf\Bundle\MediaBundle\Doctrine\Phpcr\Image;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Toro\Bundle\CmsBundle\Model\PageInterface;
use Toro\Bundle\FixtureBundle\DataFixture\Factory\AbstractLocaleAwareFactory;
use Toro\Bundle\FixtureBundle\DataFixture\Uploader\ImageUploadHelper;

final class PageExampleFactory extends AbstractLocaleAwareFactory
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
                    return $this->generateFakerWithLocale('word');
                })
                ->setDefault('slug', function (Options $options) {
                    return $this->generateFakerWithLocale('slug');
                })
                ->setDefault('body', function (Options $options) {
                    return $this->generateFakerWithLocale('paragraph');
                })

                ->setRequired(['title', 'slug', 'body'])

                ->setNormalizer('body', function (Options $options, $value) {
                    foreach ($this->getLocales() as $locale) {
                        if (null === $value[$locale]) {
                            $value[$locale] = $this->faker->paragraph;
                        }
                    }

                    return $value;
                })

                ->setNormalizer('images', function (Options $options, $value) {
                    $images = [];
                    //return $images;

                    if ($value) {
                        $parent = $this->documentManager->find(null, $this->rootFolder.'/pages');

                        foreach ($value as $item) {
                            $images[] = $image = $this->uploadFileHelper->upload($item, false);

                            if ($image) {
                                $image->setParent($parent);
                                $this->documentManager->persist($image);
                            }
                        }

                        $this->documentManager->flush();
                    }

                    return $images;
                })
        ;
    }

    private function generateFakerWithLocale($fakeName)
    {
        $values = [];

        foreach ($this->getLocales() as $locale) {
            $values[$locale] = $this->faker->$fakeName;
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

        /** @var PageInterface $object */
        $object = $this->factory->createNew();

        $this->setLocalizedData($object, [
            'title',
            'slug',
            'body',
        ], $options);

        return $object;
    }
}
// /app_dev.php/media/cache/resolve/strip/cms/media/pages/
