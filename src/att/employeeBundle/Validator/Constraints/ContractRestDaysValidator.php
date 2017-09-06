<?php

namespace att\employeeBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ContractRestDaysValidator  extends ConstraintValidator {
    
     
    public function validate($contract, Constraint $constraint) {          
        
        $rest_days_schema = 7 - $contract->getSchema()->getDays();
        $rest_days = count($contract->getRestDay());
        
        if($rest_days != $rest_days_schema){
            $this->context->buildViolation($constraint->message)
                    ->setParameter('%contract_number%', $contract->getFileNumber())
                    ->setParameter('%rest_days_qty%', $rest_days_schema())
                    ->addViolation();
        }
        
        
        
    }
}
