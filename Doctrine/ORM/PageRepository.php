<?php

namespace Toro\Bundle\CmsBundle\Doctrine\ORM;

use Doctrine\ORM\QueryBuilder;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Channel\Model\ChannelInterface;

class PageRepository extends EntityRepository implements PageRepositoryInterface, PageFinderRepositoryInterface
{
    protected function useCache(QueryBuilder $queryBuilder, $enabled = true)
    {
        $queryBuilder
            ->getQuery()
            ->useQueryCache($enabled)
            ->useResultCache($enabled)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function findPageForDisplay(array $criteria)
    {
        $criteria['published'] = true;

        return $this->findOneBy($criteria);
    }

    /**
     * {@inheritdoc}
     */
    public function findOneBy(array $criteria, array $orderBy = null)
    {
        if (empty(array_intersect_key(array('slug', 'title'), array_keys($criteria)))) {
            return parent::findOneBy($criteria, $orderBy);
        }

        $queryBuilder = $this->createQueryBuilder('o');

        $this->applyCriteria($queryBuilder, $criteria);
        $this->applySorting($queryBuilder, (array) $orderBy);

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }

    /**
     * {@inheritdoc}
     */
    public function findOneByIdAndChannel($id, ChannelInterface $channel = null)
    {
        $queryBuilder = $this->createQueryBuilder('o')
            ->innerJoin('o.channels', 'channel')
        ;

        $queryBuilder
            ->andWhere($queryBuilder->expr()->eq('o.id', ':id'))
            ->setParameter('id', $id)
        ;

        if (null !== $channel) {
            $queryBuilder
                ->andWhere('channel = :channel')
                ->setParameter('channel', $channel);
        }

        return $queryBuilder
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function findOneBySlugAndChannel($slug, ChannelInterface $channel)
    {
        return $this->createQueryBuilder('o')
            ->leftJoin('o.translations', 't')
            ->innerJoin('o.channels', 'channel')
            ->andWhere('channel = :channel')
            ->andWhere('o.enabled = true')
            ->andWhere('t.slug = :slug')
            ->setParameter('slug', $slug)
            ->setParameter('channel', $channel)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function applyCriteria(QueryBuilder $queryBuilder, array $criteria = null)
    {
        if (isset($criteria['channels'])) {
            $queryBuilder
                ->innerJoin('o.channels', 'channel')
                ->andWhere('channel = :channel')
                ->setParameter('channel', $criteria['channels'])
            ;

            unset($criteria['channels']);
        }

        if (!empty(array_intersect_key(array('slug', 'title'), array_keys($criteria)))) {
            $queryBuilder
                ->addSelect('t')
                ->leftJoin('o.translations', 't')
            ;

            if (isset($criteria['slug'])) {
                $queryBuilder
                    ->andWhere('LOWER(t.slug) = :slug')
                    ->setParameter('slug', strtolower($criteria['slug']))
                ;

                unset($criteria['slug']);
            }

            if (isset($criteria['title'])) {
                $queryBuilder
                    ->andWhere('LOWER(t.title) = :title')
                    ->setParameter('title', strtolower($criteria['title']))
                ;

                unset($criteria['title']);
            }

            if (isset($criteria['locale'])) {
                $queryBuilder
                    ->andWhere('LOWER(t.locale) = :locale')
                    ->setParameter('locale', strtolower($criteria['locale']))
                ;

                unset($criteria['locale']);
            }
        }

        $this->useCache($queryBuilder);

        parent::applyCriteria($queryBuilder, $criteria);
    }
}
