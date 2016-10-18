<?php

namespace Toro\Bundle\CmsBundle\Model;

use Sylius\Component\User\Model\UserInterface;

interface BlameableInterface
{
    /**
     * @param  UserInterface $createdBy
     */
    public function setCreatedBy(UserInterface $createdBy = null);

    /**
     * @return UserInterface
     */
    public function getCreatedBy();

    /**
     * @param  UserInterface $updatedBy
     */
    public function setUpdatedBy(UserInterface $updatedBy = null);

    /**
     * @return UserInterface|null
     */
    public function getUpdatedBy();
}
