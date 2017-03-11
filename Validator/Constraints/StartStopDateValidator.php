<?php

namespace Toro\Bundle\CmsBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class StartStopDateValidator extends ConstraintValidator
{
    /**
     * @param object $entity
     * @param Constraint|StartStopDate $constraint
     */
    public function validate($entity, Constraint $constraint)
    {
        if (!is_array($constraint->fields) && !is_string($constraint->fields)) {
            throw new UnexpectedTypeException($constraint->fields, 'array');
        }

        if (null !== $constraint->errorPath && !is_string($constraint->errorPath)) {
            throw new UnexpectedTypeException($constraint->errorPath, 'string or null');
        }

        $fields = (array) $constraint->fields;

        if (0 === count($fields)) {
            throw new ConstraintDefinitionException('At least one field has to be specified.');
        }

        if (!$startedAt = $entity->getStartedAt()) {
            return;
        }

        if (!$stopedAt = $entity->getStopedAt()) {
            return;
        }

        if (!$constraint->checkTime) {
            /** @var \DateTime $startedAt */
            /** @var \DateTime $stopedAt */
            $startedAt = clone $startedAt;
            $stopedAt = clone $stopedAt;

            $startedAt->setTime(0, 0, 0);
            $stopedAt->setTime(0, 0, 0);
        }

        if ($startedAt >= $stopedAt) {
            $errorPath = null !== $constraint->errorPath ? $constraint->errorPath : $fields[0];
            $this->context->buildViolation($constraint->message)
                ->atPath($errorPath)
                ->addViolation()
            ;
        }
    }
}
