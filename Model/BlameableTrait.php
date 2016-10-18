<?php

namespace Toro\Bundle\CmsBundle\Model;

use Sylius\Component\User\Model\UserInterface;

trait BlameableTrait
{
    /**
     * @var UserInterface
     */
    private $createdBy;

    /**
     * @var UserInterface
     */
    private $updatedBy;

    /**
     * @param  UserInterface $createdBy
     */
    public function setCreatedBy(UserInterface $createdBy = null)
    {
        $this->createdBy = $createdBy;
    }

    /**
     * @return UserInterface
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @param  UserInterface $updatedBy
     */
    public function setUpdatedBy(UserInterface $updatedBy = null)
    {
        $this->updatedBy = $updatedBy;
    }

    /**
     * @return UserInterface|null
     */
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }
}
