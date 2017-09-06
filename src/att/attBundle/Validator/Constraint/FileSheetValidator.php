<?php

namespace att\attBundle\Validator\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\DependencyInjection\Container;

class CertWfCheckValidator extends ConstraintValidator {
    
    protected $em;
    protected $workflow = FALSE;
    
    public function __construct(Container $container) {
        
        $this->container = $container;        
                
    }
    
    public function validate($entity, Constraint $constraint) {
        
        $this->workflow=$this->em
                    ->getRepository('attBundle:Atworkflow')
                    ->findOneByEntityid($entity->getId());
          
        
        if ($this->workflow){
            
            $this->context->buildViolation($constraint->message)
                    ->setParameter('%string%', $entity)
                ->addViolation();
        }
    }


 
}
