<?php

namespace att\attBundle\Repository;
use Doctrine\ORM\EntityRepository;

class AtabsenceRepository extends EntityRepository {
    
    public function findBetweenDate($from, $to){
        
        $qb = $this->createQueryBuilder('abs');
        $qb
           ->addSelect('att')
           ->addSelect('plan')
           ->leftJoin('abs.attendance', 'att')
           ->leftJoin('att.plan', 'plan')
           ->where($qb->expr()->between('plan.date', ':from', ':to'))
           ->setParameters([
                            'from' => $from,
                            'to'   => $to
                          ]);
        return $qb->getQuery()->getResult(); 
    }
}
