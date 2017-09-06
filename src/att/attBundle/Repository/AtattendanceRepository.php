<?php

namespace att\attBundle\Repository;

use Doctrine\ORM\EntityRepository;

class AtattendanceRepository extends EntityRepository {
    
    
    public function findByDate(\DateTime $day){
        
        $qb = $this->createQueryBuilder('a');
        $qb->addSelect('p')
           ->leftJoin('a.plan', 'p')    
           ->where('p.date = :day')
           ->setParameter(':day', $day->format('Y-m-d'));
        
        return $qb->getQuery()->getResult();
                
    }
    
        
    public function findBetweenDates(\DateTime $from, \DateTime $to){
       
       $qb = $this->createQueryBuilder('a');
       $qb->addSelect('p')
          ->leftJoin('a.plan', 'p')     
          ->where($qb->expr()->between('p.date', ':from', ':to'))
          ->setParameter('from', $from)
          ->setParameter('to', $to);
       
       return $qb->getQuery()->getResult();
               
   }
   
   
    public function findBetweenInPlanDates(\DateTime $from, \DateTime $to){
       
       $qb = $this->createQueryBuilder('a');
       $qb->addSelect('p')
          ->leftJoin('a.plan', 'p')     
          ->where($qb->expr()->between('p.inplan', ':from', ':to'))
          ->setParameter('from', $from)
          ->setParameter('to', $to);
       
       return $qb->getQuery()->getResult();
               
   }
   
   public function resumePresenteeismByDate(\DateTime $from, \DateTime $to){
        
       $q = $this->getEntityManager()->createQuery('SELECT '
               . 'cia.description as Company, '
               . 'b.description as Business, '
               . 'SUM(CASE when a.stateattendance = 1 THEN 1 ELSE 0 END) as Prensents, '
               . 'SUM(CASE when a.stateattendance = 2 THEN 1 ELSE 0 END) as Absents, '
               . '((SUM(CASE when a.stateattendance = 1 THEN 1 ELSE 0 END)) + (SUM(CASE when a.stateattendance = 2 THEN 1 ELSE 0 END))) as Planned '
               
               . 'FROM attBundle:Atattendance a '
               . 'LEFT JOIN attBundle:Atplan p WITH p.id = a.plan '
               . 'LEFT JOIN attBundle:Atstateplan sp WITH  sp.id = p.stateplan '
               . 'LEFT JOIN employeeBundle:Atemployee e WITH e.id = p.employee '
               . 'LEFT JOIN employeeBundle:Atcontract c WITH c.employee = e.id '
               . 'LEFT JOIN employeeBundle:Atbusiness b WITH c.business = b.id '
               . 'LEFT JOIN employeeBundle:Atcompany cia WITH cia.id = b.company '
               . 'WHERE p.date BETWEEN :datefrom and :dateto '
               . 'GROUP BY b.id '
               );
       $q->setParameter('datefrom', $from);
       $q->setParameter('dateto', $to);
       
       return $q->getResult();
   }
}
