<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class MaxQuantityOfTickets extends Constraint
{
    public function getTargets()
    {
        return parent::CLASS_CONSTRAINT;
    }

    public function getMessage()
    {
        return 'Nombre de tickets restants pour cette date : NBTICKET';
    }
}
