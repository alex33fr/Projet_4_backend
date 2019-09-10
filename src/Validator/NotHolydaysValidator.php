<?php

namespace App\Validator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class NotHolydaysValidator extends ConstraintValidator
{

    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint \App\Validator\NotHolydays */

        if (!$value instanceof  \DateTime) {
            return;
        }

        $holidays = array(
            // Jours fériés fixes
            mktime(0, 0, 0, 5, 1, $value->format('Y')),// Fete du travail
            mktime(0, 0, 0, 11, 1, $value->format('Y')),// Toussaint
            mktime(0, 0, 0, 12, 25, $value->format('Y')),// Noël
        );
        if (in_array($value->format('U'), $holidays)) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }

}
