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

    /**
     * {@inheritdoc}
     */
    public function createFilterQueryBuilder($locale, $rootCode = null)
    {
        $queryBuilder = $this->createQueryBuilder('o');

        if ($rootCode) {
            $queryBuilder
                ->innerJoin('o.root', 'root')
                ->andWhere('root.code = :rootCode')
                ->setParameter('rootCode', $rootCode)
            ;
        }

        return $queryBuilder
            ->addSelect('translation')
            ->addSelect('parent')
            ->innerJoin('o.parent', 'parent')
            ->innerJoin('o.translations', 'translation', 'WITH', 'translation.locale = :locale')
            ->andWhere($queryBuilder->expr()->between('o.left', 'parent.left', 'parent.right'))
            ->setParameter('locale', $locale)
            ->addOrderBy('o.left')
        ;
    }
}
