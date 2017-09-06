<?php

namespace att\ctrlaccBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * AttendanceRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AttendanceRepository extends EntityRepository
{
    
    public function findByEmployeesAndDay(\DateTime $day, array $employees = null){
    
        $qb = $this->createQueryBuilder('a');
        $qb->where('a.date = :date')
           ->setParameter('date', $day->format('Y-m-d'));
        
        if($employees){
           $qb->andWhere($qb->expr()->in('a.employee', ':employees'))
               ->setParameter('employees', $employees);
        }
        
        $attendances = $qb->getQuery()->getResult();
        
        return $attendances;
    }
    
    
    
}