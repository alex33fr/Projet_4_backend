<?php

namespace App\Validator;


use App\Repository\BookingRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class NotHolydaysValidator extends ConstraintValidator
{

    public function __construct(BookingRepository $bookingRepository)
    {
    }

    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint \App\Validator\NotHolydays */

        if (!$value instanceof  \DateTime) {
            return;
        }

        $easterDate = easter_date($value->format('Y'));
        $easterDay = date('j', $easterDate);
        $easterMonth = date('n', $easterDate);
        $easterYear = date('Y', $easterDate);

        $holidays = array(
            // Jours feries fixes
            mktime(0, 0, 0, 1, 1, $value->format('Y')),// 1er janvier
            mktime(0, 0, 0, 5, 1, $value->format('Y')),// Fete du travail
            mktime(0, 0, 0, 5, 8, $value->format('Y')),// Victoire des allies
            mktime(0, 0, 0, 7, 14, $value->format('Y')),// Fete nationale
            mktime(0, 0, 0, 8, 15, $value->format('Y')),// Assomption
            mktime(0, 0, 0, 11, 1, $value->format('Y')),// Toussaint
            mktime(0, 0, 0, 11, 11, $value->format('Y')),// Armistice
            mktime(0, 0, 0, 12, 25, $value->format('Y')),// Noel
            mktime(0, 0, 0, $easterMonth, $easterDay + 1, $easterYear),// Lundi de paques
            mktime(0, 0, 0, $easterMonth, $easterDay + 39, $easterYear),// Ascension
            mktime(0, 0, 0, $easterMonth, $easterDay + 50, $easterYear), // Pentecote
        );
        if (in_array($value->format('U'), $holidays)) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }

}
