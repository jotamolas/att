<?php

namespace att\employeeBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class CheckContract extends Constraint {
    
    public $message = "This employee have an active Contract";
    
    public function validatedBy() {
        return "validator.checkcontract";
    }
    
    public function getTargets() {
        return self::CLASS_CONSTRAINT;
    }
    
}
