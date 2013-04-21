<?php

// src/Acme/DemoBundle/Validator/Constraints/ContainsAlphanumericValidator.php
namespace Application\EservicesBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ContreainteUniqueValidator extends ConstraintValidator
{
    public function isValid($value, Constraint $constraint)
    {
        if (!preg_match('/^[a-zA-Za0-9]+$/', $value, $matches)) {
            $this->setMessage($constraint->message, array('%string%' => $value));

            return false;
        }

        return true;
    }
}