<?php

namespace att\employeeBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class ContractRestDays extends Constraint {
    
    public $message = "The Contract {{ contract_number }}  must have {{ rest_days_qty } rest days according to the scheme set";
            
          
    public function validatedBy() {
        return "validator.contract.restdays";
    }
    
    public function getTargets() {
        return self::CLASS_CONSTRAINT;
    }
    
}

