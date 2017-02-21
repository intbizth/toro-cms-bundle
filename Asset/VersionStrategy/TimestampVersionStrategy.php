<?php

namespace Toro\Bundle\CmsBundle\Asset\VersionStrategy;

use Symfony\Component\Asset\VersionStrategy\StaticVersionStrategy;

class TimestampVersionStrategy extends StaticVersionStrategy
{
    public function __construct($format = null)
    {
        parent::__construct(null, $format);
    }

    public function getVersion($path)
    {
        return time();
    }
}
