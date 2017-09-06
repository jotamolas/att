<?php

namespace att\employeeBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class SchemaSchedule extends Constraint {
    
    public $message = "Planned hours must match schema hours";
    
    public function validatedBy() {
        return "validator.schema.schedule";
    }
    
    public function getTargets() {
        return self::CLASS_CONSTRAINT;
    }
    
}
