<?php

namespace att\employeeBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ContractFileNumber extends Constraint {
    
    public $message = "There is already a file number for this business";
    
    public function validatedBy() {
        return "validator.filenumber";
    }
    
    public function getTargets() {
        return self::CLASS_CONSTRAINT;
    }
    
}
