<?php


namespace att\employeeBundle\Repository;

use Doctrine\ORM\EntityRepository;

class AtcompanyRepository  extends EntityRepository{
    
    public function getDescriptions(){
        
        $qb = $this->createQueryBuilder('c');
        $qb->select('c.description');
        $result = $qb->getQuery()->getScalarResult();
        return array_map('current', $result);

    }
}
