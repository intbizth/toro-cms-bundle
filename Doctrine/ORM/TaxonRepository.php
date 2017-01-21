<?php

namespace Toro\Bundle\CmsBundle\Doctrine\ORM;

use Sylius\Bundle\TaxonomyBundle\Doctrine\ORM\TaxonRepository as BaseTaxonRepository;

class TaxonRepository extends BaseTaxonRepository
{
    /**
     * {@override}
     */
    public function findChildren($taxonCode)
    {
        if (!$taxon = $this->findOneBy(['code' => $taxonCode])) {
            return [];
        }

        $root = $taxon->isRoot() ? $taxon : $taxon->getRoot();
        $queryBuilder = $this->createQueryBuilder('o');

        $queryBuilder
            ->andWhere($queryBuilder->expr()->eq('o.root', ':root'))
            ->andWhere($queryBuilder->expr()->gt('o.left', ':left'))
            ->andWhere($queryBuilder->expr()->lt('o.right', ':right'))
            ->setParameter('root', $root)
            ->setParameter('left', $taxon->getLeft())
            ->setParameter('right', $taxon->getRight())

            ->orderBy('o.left', 'ASC')
        ;

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * {@override}
     */
    public function findNodesTreeSorted()
    {
        $taxons = [];
        $roots = $this->findRootNodes();

        foreach ($this->findRootNodes() as $root) {
            $taxons[] = $root;

            $taxons = array_merge($taxons, $this->findChildren($root->getCode()));
        }

        return $taxons;
    }
}
