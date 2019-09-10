<?php

namespace App\Validator;


use App\Entity\Booking;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class NotSundayValidator extends ConstraintValidator
{


    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint \App\Validator\NotSunday */

        if (!$value instanceof  \DateTime) {
            return;
        }

        if($value->format('w') === "0"){
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }

}
