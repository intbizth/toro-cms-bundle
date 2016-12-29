<?php

namespace Toro\Bundle\CmsBundle\Model;

interface DisableDeletionInterface
{
    /**
     * @param boolean $deletable
     */
    public function setDeletable($deletable);

    /**
     * @return boolean
     */
    public function isDeletable();
}
