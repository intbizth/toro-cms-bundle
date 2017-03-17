<?php

namespace Toro\Bundle\CmsBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class Year extends Constraint
{
    public $message = 'Year must be start at {{ min }} to {{ max }}.';
    public $min = 1960;
    public $max;

    /**
     * {@inheritdoc}
     */
    public function getTargets()
    {
        return self::PROPERTY_CONSTRAINT;
    }
}
