<?php

namespace Toro\Bundle\CmsBundle\Form\Type;

use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Component\User\Model\UserInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Toro\Bundle\CmsBundle\Model\LikeableInterface;
use Toro\Bundle\CmsBundle\Model\LikeInterface;

class LikeableType extends AbstractResourceType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->addEventListener(FormEvents::POST_SET_DATA, function(FormEvent $event) use ($options) {
                /** @var LikeableInterface $likeable */
                if (!$likeable = $event->getData()) {
                    return;
                }

                $isLiked = 'like' === strtolower($options['type']);
                $likes = $this->fineLikes($options['likeRepository'], $likeable, $options['user']);

                /** @var LikeInterface $like */
                $like = $options['likeFactory']->createNew();
                $like->setLikeable($likeable);
                $like->setCreatedBy($options['user']);

                if ($isLiked) {
                    $this->createLiked($options['likeRepository'], $likes, $likeable, $like);
                } else {
                    $this->createDisliked($options['likeRepository'], $likes, $likeable, $like);
                }

                foreach ($likes as $like) {
                    $options['likeRepository']->remove($like);
                }
            })
        ;
    }

    /**
     * @param EntityRepository $repository
     * @param array $likes
     * @param LikeableInterface $likeable
     * @param LikeInterface $like
     */
    private function createLiked(EntityRepository $repository, array $likes, LikeableInterface $likeable, LikeInterface $like)
    {
        $hasLiked = false;

        foreach ($likes as $likeObject) {
            // this mean unlike
            if ($likeObject->isLiked()) {
                $hasLiked = true;
                $likeable->unlike();
            } else {
                // if dislike exit increase them too
                $likeable->undislike();
            }
        }

        if ($hasLiked) {
            return;
        }

        $likeable->like();
        $repository->add($like);
    }

    /**
     * @param EntityRepository $repository
     * @param array $likes
     * @param LikeableInterface $likeable
     * @param LikeInterface $dislike
     */
    private function createDisliked(EntityRepository $repository, array $likes, LikeableInterface $likeable, LikeInterface $dislike)
    {
        $hasDisliked = false;

        foreach ($likes as $dislikeObject) {
            // this mean undislike
            if (!$dislikeObject->isLiked()) {
                $hasDisliked = true;
                $likeable->undislike();
            } else {
                // if like exit increase them too
                $likeable->unlike();
            }
        }

        if ($hasDisliked) {
            return;
        }

        $dislike->setLiked(false);
        $likeable->dislike();
        $repository->add($dislike);
    }

    /**
     * @param EntityRepository $repository
     * @param LikeableInterface $likeable
     * @param UserInterface $user
     *
     * @return LikeInterface[]
     */
    private function fineLikes(EntityRepository $repository, LikeableInterface $likeable, UserInterface $user)
    {
        return $repository->findBy([
            'createdBy' => $user,
            'likeable' => $likeable,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'type' => null,
            'likeRepository' => null,
            'likeFactory' => null,
            'user' => null,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'toro_likeable';
    }
}
