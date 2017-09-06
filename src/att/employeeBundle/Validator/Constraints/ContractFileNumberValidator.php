<?php

namespace att\employeeBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManager;

class ContractFileNumberValidator  extends ConstraintValidator {
    
    protected $em;
    protected $filenumber;
    
    public function __construct(EntityManager $em) {
        $this->em = $em;
    }
    
    public function validate($entity, Constraint $constraint) {          

        
        $this->filenumber = $this->em
                ->getRepository('employeeBundle:Atcontract')
                ->createQueryBuilder('c')
                ->select('c.file_number')
                ->leftJoin('c.business', 'b')
                ->where('b.id = :b')
                ->andWhere('c.file_number = :f')
                ->setParameter('f', $entity->getFileNumber())
                ->setParameter('b', $entity->getBusiness()->getId())
                ->getQuery()
                ->getResult();
        

        if($this->filenumber){
            $this->context->buildViolation($constraint->message)
                    ->setParameter('%string%', $entity)
                    ->addViolation();
        }
        
        
        
    }
}
