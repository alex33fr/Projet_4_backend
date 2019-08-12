<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class NotHolydays extends Constraint
{

    public $message = 'Vous ne pouvez pas réserver un jour férié';
}
