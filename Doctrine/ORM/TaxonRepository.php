<?php

namespace Toro\Bundle\CmsBundle\Doctrine\ORM;

use Sylius\Bundle\TaxonomyBundle\Doctrine\ORM\TaxonRepository as BaseTaxonRepository;

class TaxonRepository extends BaseTaxonRepository
{
    /**
     * @bugfix https://github.com/Sylius/Sylius/pull/7437
     *
     * {@inheritdoc}
     */
    public function findNodesTreeSorted($rootCode = null)
    {
        $queryBuilder = $this->createQueryBuilder('o')
            ->addOrderBy('o.root')
            ->addOrderBy('o.left')
        ;

        if (null !== $rootCode) {
            $queryBuilder
                ->join('o.root', 'root')
                ->andWhere('root.code = :rootCode')
                ->setParameter('rootCode', $rootCode)
            ;
        }

        return $queryBuilder->getQuery()->getResult();
    }
}
