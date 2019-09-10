<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class NotFullDay extends Constraint
{
    public $hour;

    public function getTargets()
    {
        return parent::CLASS_CONSTRAINT;
    }

    public function getMessage()
    {
        return 'Vous pouvez commander un billet pour le jour même mais vous ne pouvez plus commander de billet « Journée » une fois '.$this->hour.'h00 passées.';
    }
}
