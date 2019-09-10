<?php

namespace App\Validator;


use App\Entity\Booking;
use App\Repository\BookingRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class NotFullDayValidator extends ConstraintValidator
{


    public function validate($booking, Constraint $constraint)
    {
        /** @var Booking $booking */
        /* @var $constraint \App\Validator\NotFullDay */
        $hour = date('H');
        $date = date('Ymd');
        if ($booking->getDurationType() == Booking::TYPE_DAY &&
            $booking->getVisitDate()->format('Ymd') == $date &&
            $hour >= $constraint->hour
        ){
                $this->context->buildViolation($constraint->getMessage())
                    ->atPath('durationType')
                    ->addViolation();
        }

    }

}
