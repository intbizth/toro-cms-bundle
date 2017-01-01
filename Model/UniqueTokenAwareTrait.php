<?php

namespace Toro\Bundle\CmsBundle\Model;

trait UniqueTokenAwareTrait
{
    /**
     * @var string
     */
    protected $uniqueToken;

    /**
     * @return string
     */
    public function getUniqueToken()
    {
        return $this->uniqueToken;
    }

    /**
     * @param string $uniqueToken
     */
    public function setUniqueToken($uniqueToken)
    {
        $this->uniqueToken = $uniqueToken;
    }
}
