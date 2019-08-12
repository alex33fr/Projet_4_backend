<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class NotTuesdayValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint \App\Validator\NotTuesday */

        if (!$value instanceof  \DateTime) {
            return;
        }

        if($value->format('w') === "2"){
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
