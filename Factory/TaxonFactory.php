<?php

namespace Toro\Bundle\CmsBundle\Factory;

use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;

class TaxonFactory implements FactoryInterface
{
    /**
     * @var FactoryInterface
     */
    private $decoratedFactory;

    public function __construct(FactoryInterface $decoratedFactory)
    {
        $this->decoratedFactory = $decoratedFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function createNew()
    {
        return $this->decoratedFactory->createNew();
    }

    /**
     * @param TaxonInterface|null $taxon
     *
     * @return TaxonInterface
     */
    public function createWithParent(TaxonInterface $taxon = null)
    {
        /** @var TaxonInterface $resource */
        $resource = $this->createNew();
        $resource->setParent($taxon);

        return $resource;
    }
}
