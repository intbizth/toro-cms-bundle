<?php

namespace Toro\Bundle\CmsBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class YoutubeEmbed extends Constraint
{
    public $message = 'Please use embed url.';
}
