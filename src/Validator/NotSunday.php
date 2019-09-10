<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class NotSunday extends Constraint
{
    public $message = 'Vous ne pouvez pas réserver un dimanche';
}
