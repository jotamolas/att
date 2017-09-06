<?php

namespace att\employeeBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class SchemaScheduleValidator extends ConstraintValidator {

    protected $em;
    protected $activeContract;

    public function validate($entity, Constraint $constraint) {


        $intime = $entity->getInTime();
        $outtime = $entity->getOutTime();      
        
        $outtime < $intime ? $outtime->modify('+1 day') : null;
        
        $diff = $outtime->diff($intime);
        

        if ($entity->getSchema()->getHours() != ($diff->h+($diff->i/60))) {
            $this->context->buildViolation($constraint->message)
                    ->setParameter('%string%', $entity)
                    ->addViolation();
        }
    }

}
 