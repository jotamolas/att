<?php

namespace att\attBundle\Validator\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManager;

class CertWfCheckValidator extends ConstraintValidator {

    protected $em;
    protected $workflow = FALSE;

    public function __construct(EntityManager $em) {

        $this->em = $em;
    }

    public function validate($entity, Constraint $constraint) {

        $this->workflow = $this->em
                ->getRepository('attBundle:Atworkflow')
                ->findOneByEntityid($entity->getId());


        if ($this->workflow) {


            /* $this->buildViolation($constraint->message)
              ->setParameter('%string%', $entity)
              ->addViolation(); */

            $this->context->buildViolation($constraint->message)
                    ->setParameter('%string%', $entity)
                    ->addViolation();
        }
    }

}

/**
 *    public function validate($entity) {
        
        var_dump($entity);
    }
 */