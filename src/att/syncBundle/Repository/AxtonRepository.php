<?php

namespace att\syncBundle\Repository;

use Doctrine\ORM\EntityRepository;

class AxtonRepository extends EntityRepository{
    
    
    public function findEmpresas($in = null , $notin = null){
        
        $qb = $this->createQueryBuilder('c');
        $qb->select("c.empresa");
        $in ? $qb->orWhere($qb->expr()->in('c.empresa', $in)) : null;
        $notin ? $qb->orWhere($qb->expr()->notIn('c.empresa', $notin)) : null;
        $qb->groupBy("c.empresa");
        
        return $qb->getQuery()->getResult();
        
    }
    
    
    public function findNegocios($in = null , $notin= null){
        
        $qb = $this->createQueryBuilder('b');
        $qb->select($qb->expr()->trim("b.empresa"))
           ->addSelect($qb->expr()->trim("b.provincia"));
        
        if($in){
            foreach ($in as $i){
                
                $qb->orWhere(
                        $qb->expr()->andX($qb->expr()->eq('b.empresa', "'".$i['description']."'"), $qb->expr()->eq('b.provincia', "'".$i['state']."'"))
                        
                        );
            }
        }
        
        if($notin){
            foreach ($notin as $i){
                
                $qb->orWhere(
                        "(b.empresa, b.provincia) <> ('".$i['description']."','".$i['state']."')"
                        );
                
                
                /*$qb->orWhere(
                        $qb->expr()->andX($qb->expr()->neq('b.empresa', "'".$i['description']."'"), $qb->expr()->neq('b.provincia', "'".$i['state']."'"))
                        
                        );*/
                
            }
        }
        
        $qb->groupBy('b.empresa, b.provincia');
        
        return $qb->getQuery()->getResult();        
        
    }
    
    
    
       public function findSQLNegocios($in = null , $notin= null){
        
         $qb = $this->createQueryBuilder('e');

         return $qb->select('e.empresa, e.provincia')
            
            ->where($qb->expr()->notIn('e.empresa', ':excludedCompanies'))
            ->orWhere($qb->expr()->notIn('e.provincia', ':excludedStates'))
            ->setParameter('excludedCompanies', $notin['companies'])
            ->setParameter('excludedStates', $notin['states'])
            ->groupBy('e.empresa')
            ->addGroupBy('e.provincia')
            ->getQuery()
            ->getResult();
            
    }
}
