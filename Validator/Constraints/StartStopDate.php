<?php

namespace Toro\Bundle\CmsBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class StartStopDate extends Constraint
{
    public $message = 'Start date must less than stop date.';
    public $fields = array();
    public $errorPath = null;

    /**
     * {@inheritdoc}
     */
    public function getRequiredOptions()
    {
        return array('fields');
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultOption()
    {
        return 'fields';
    }

    /**
     * {@inheritdoc}
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
