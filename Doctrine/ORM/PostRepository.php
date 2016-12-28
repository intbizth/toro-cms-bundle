<?php

namespace Toro\Bundle\CmsBundle\Doctrine\ORM;

class PostRepository extends PageRepository
{
    /**
     * {@inheritdoc}
     */
    public function findPageForDisplay(array $criteria)
    {
        $criteria['published'] = true;

        if (array_key_exists('partial', $criteria)) {
            unset($criteria['partial']);
        }

        return parent::findPageForDisplay($criteria);
    }
}
