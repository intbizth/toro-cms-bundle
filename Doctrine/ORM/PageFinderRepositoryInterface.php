<?php

namespace Toro\Bundle\CmsBundle\Doctrine\ORM;

interface PageFinderRepositoryInterface
{
    /**
     * @param array $criteria
     *
     * @return mixed
     */
    public function findPageForDisplay(array $criteria);
}
