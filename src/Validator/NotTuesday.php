<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class NotTuesday extends Constraint
{

    public $message = 'Vous ne pouvez pas réserver un mardi';
}
