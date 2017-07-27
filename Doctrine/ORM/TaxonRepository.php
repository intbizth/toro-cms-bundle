<?php

namespace Toro\Bundle\CmsBundle\Doctrine\ORM;

use Sylius\Bundle\TaxonomyBundle\Doctrine\ORM\TaxonRepository as BaseTaxonRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TaxonRepository extends BaseTaxonRepository
{
    /**
     * {@inheritdoc}
     */
    public function findNodesTreeSorted($rootCode = null)
    {
        $queryBuilder = $this->createQueryBuilder('o')
            ->addOrderBy('o.root')
            ->addOrderBy('o.left')

            ->addSelect('t')
            ->join('o.translations', 't')
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

    public function findOneBySlugOr404($slug, $locale)
    {
        if (!$taxon = $this->createQueryBuilder('o')
            ->addSelect('translation')
            ->innerJoin('o.translations', 'translation')
            ->andWhere('translation.slug = :slug')
            ->andWhere('translation.locale = :locale')
            ->setParameter('slug', $slug)
            ->setParameter('locale', $locale)
            ->getQuery()
            ->getOneOrNullResult()) {
            throw new NotFoundHttpException(sprintf('The "%s" has not been found', $slug));
        }

        return $taxon;
    }
}
