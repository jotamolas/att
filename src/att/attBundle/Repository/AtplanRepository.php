<?php

namespace att\attBundle\Repository;

use Doctrine\ORM\EntityRepository;


class AtplanRepository extends EntityRepository {
    
   public function findBetweenDates(\DateTime $from, \DateTime $to){
       
       $qb = $this->createQueryBuilder('p');
       $qb->where($qb->expr()->between('p.date', ':from', ':to'))
          ->setParameters([
              'from' => $from,
              'to' => $to
                  ])
          ;
       
       return $qb->getQuery()->getResult();
               
   }
   public function findByEmployeeAndBetweenDates(\att\employeeBundle\Entity\Atemployee $employee, \DateTime $from, \DateTime $to){
       
       $qb = $this->createQueryBuilder('p');
       $qb->where($qb->expr()->between('p.date', ':from', ':to'))
          ->setParameters([
              'from' => $from,
              'to' => $to
                  ])
          ->andWhere($qb->expr()->eq('p.employee', ':employee'))
          ->setParameter('employee', $employee);
       
       return $qb->getQuery()->getResult();
               
   }
   
    
    
}
