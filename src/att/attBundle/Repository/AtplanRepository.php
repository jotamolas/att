<?php

namespace att\attBundle\Repository;

use Doctrine\ORM\EntityRepository;


class AtplanRepository extends EntityRepository {
    
   public function findBetweenDates(\DateTime $from, \DateTime $to){
       
       $qb = $this->createQueryBuilder('p');
       $qb->where($qb->expr()->between('p.date', ':from', ':to'))
          ->setParameter('from', $from)
          ->setParameter('to', $to);
       
       return $qb->getQuery()->getResult();
               
   }
    
    
}
