<?php

namespace Toro\Bundle\CmsBundle\Model;

interface UniqueTokenAwareInterface
{
    /**
     * @return string
     */
    public function getUniqueToken();

    /**
     * @param string $uniqueToken
     */
    public function setUniqueToken($uniqueToken);
}
