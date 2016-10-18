<?php

namespace Toro\Bundle\CmsBundle\Model;

interface CompileAwareContentInterface
{
    /**
     * @return string
     */
    public function getCompileContent();
}
