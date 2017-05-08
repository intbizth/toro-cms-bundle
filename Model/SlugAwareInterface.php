<?php

namespace Toro\Bundle\CmsBundle\Model;

interface SlugAwareInterface
{
    /**
     * @return string
     */
    public function getSlugable();

    /**
     * @return string
     */
    public function getSlug();

    /**
     * @param string $slug
     */
    public function setSlug($slug = null);
}
