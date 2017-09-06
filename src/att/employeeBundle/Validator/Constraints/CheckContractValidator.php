<?php

namespace att\employeeBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManager;

class CheckContractValidator  extends ConstraintValidator {
    
    protected $em;
    protected $activeContract;
    
    public function __construct(EntityManager $em) {
        $this->em = $em;
    }
    
    public function validate($entity, Constraint $constraint) {          
        
        /*print "<pre>";
        var_dump($entity);
        print "</pre>";*/
        
        $this->activeContract = $this->em
                ->getRepository('employeeBundle:Atcontract')
                ->createQueryBuilder('c')
                ->leftJoin('c.status', 's')
                ->leftJoin('c.employee', 'e')
                ->where('s.description = :s')
                ->andWhere('e.id = :e')
                ->setParameter('s', 'Active')
                ->setParameter('e', $entity->getId())
                ->getQuery()
                ->getResult();
        
        
        
        if($this->activeContract){
            $this->context->buildViolation($constraint->message)
                    ->setParameter('%string%', $entity)
                    ->addViolation();
        }
        
        
        
    }
}
