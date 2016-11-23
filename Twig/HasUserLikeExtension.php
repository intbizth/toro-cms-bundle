<?php

namespace Toro\Bundle\CmsBundle\Twig;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\QueryBuilder;
use Sylius\Component\User\Model\UserInterface;
use Toro\Bundle\CmsBundle\Model\LikeableInterface;

class HasUserLikeExtension extends \Twig_Extension
{
    /**
     * @var ManagerRegistry
     */
    private $registry;

    /**
     * @var ObjectRepository[]
     */
    private $repository = [];

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('cms_has_user_like', array($this, 'hasLike')),
            new \Twig_SimpleFunction('cms_has_user_dislike', array($this, 'hasDislike')),
        );
    }

    /**
     * @param LikeableInterface $likeable
     * @return ObjectRepository
     */
    private function getRepositoryForObject(LikeableInterface $likeable)
    {
        // FIXME: use like object by some registry
        $class = get_class($likeable).'Like';

        if (!empty($this->repository[$class])) {
            return $this->repository[$class];
        }

        return $this->repository[$class] = $this->registry->getRepository($class);
    }

    private function count(LikeableInterface $likeable, UserInterface $user, $liked)
    {
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $this->getRepositoryForObject($likeable)->createQueryBuilder('o');

        return $queryBuilder
            ->select('COUNT(o)')
            ->where('o.liked = :liked')->setParameter('liked', $liked)
            ->andWhere('o.likeable = :likeable')->setParameter('likeable', $likeable)
            ->andWhere('o.createdBy = :createdBy')->setParameter('createdBy', $user)
            ->getQuery()
            ->useQueryCache(true)
            ->useResultCache(true)
            ->getSingleScalarResult()
        ;
    }

    /**
     * @param LikeableInterface $likeable
     * @param UserInterface|null $user
     *
     * @return bool
     */
    public function hasLike(LikeableInterface $likeable, UserInterface $user = null)
    {
        if (null === $user) {
            return false;
        }

        return $this->count($likeable, $user, true);
    }


    /**
     * @param LikeableInterface $likeable
     * @param UserInterface|null $user
     *
     * @return bool
     */
    public function hasDislike(LikeableInterface $likeable, UserInterface $user = null)
    {
        if (null === $user) {
            return false;
        }

        return $this->count($likeable, $user, false);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'toro_cms_likeable';
    }
}
