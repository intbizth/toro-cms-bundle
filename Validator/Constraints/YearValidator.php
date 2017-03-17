<?php

namespace Toro\Bundle\CmsBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class YearValidator extends ConstraintValidator
{
    /**
     * @param object $value
     * @param Constraint|Year $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$value) {
            return;
        }

        if (!$constraint->max) {
            $constraint->max = date('Y');
        }

        if ($constraint->min < 1960 || $constraint->max < 1960) {
            throw new ConstraintDefinitionException('Year (min/max) must be at least 1960.');
        }

        if (intval($value) < intval($constraint->min) || intval($value) > intval($constraint->max)) {
            $this->context
                ->buildViolation($constraint->message, [
                    '{{ min }}' => $constraint->min,
                    '{{ max }}' => $constraint->max,
                ])
                ->addViolation()
            ;
        }
    }
}
