<?php

namespace att\attBundle\Repository;
use Doctrine\ORM\EntityRepository;

class NotificationRepository extends EntityRepository {
    
    public function sumToDay(\DateTime $date){
        
        
        //tomar todas las notificaticaciones iterar los dias y verificar si coincide con el date, sumar todos y presentar.
        
        $qb = $this->createQueryBuilder('n');
        $qb
           ->where($qb->expr()->gte('n.todate', ':date'))
           ->andWhere($qb->expr()->lte('n.fromdate', ':date'))      
           ->setParameters([
                            'date' => $date,
                          ]);
        return  $qb->getQuery()->getResult();
        
        
        
    }
}
