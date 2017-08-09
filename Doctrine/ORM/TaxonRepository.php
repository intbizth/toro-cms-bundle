<?php

namespace Toro\Bundle\CmsBundle\Doctrine\ORM;

use Sylius\Bundle\TaxonomyBundle\Doctrine\ORM\TaxonRepository as BaseTaxonRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TaxonRepository extends BaseTaxonRepository
{
    /**
     * {@inheritdoc}
     */
     public function findNodesTreeSorted($parentCode = null)
    {
        $queryBuilder = $this->createQueryBuilder('o')
            ->addSelect('t')
            ->join('o.translations', 't')
        ;

        if ($parentCode) {
            /** @var TaxonInterface $parent */
            if (!$parent = $this->findOneBy(['code' => $parentCode])) {
                return [];
            }

            return $queryBuilder
                ->andWhere($queryBuilder->expr()->between('o.left', $parent->getLeft(), $parent->getRight()))
                ->andWhere('o.root = :rootCode')
                ->setParameter('rootCode', $parent->getRoot())
                ->addOrderBy('o.left')
                ->getQuery()->getResult()
            ;
        }

        return $queryBuilder
            ->addSelect('parent')
            ->innerJoin('o.parent', 'parent')
            ->andWhere($queryBuilder->expr()->between('o.left', 'parent.left', 'parent.right'))
            ->addOrderBy('o.left')
            ->getQuery()->getResult()
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function createFilterQueryBuilder($locale, $rootCode = null)
    {
        $queryBuilder = $this->createQueryBuilder('o');

        if ($rootCode) {
            if (!$root = $this->findOneBy(['code' => $rootCode])) {
                return [];
            }

            return $queryBuilder
                ->andWhere($queryBuilder->expr()->between('o.left', $root->getLeft(), $root->getRight()))
                ->addOrderBy('o.left')
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
