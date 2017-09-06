<?php
namespace att\employeeBundle\Repository;

use Doctrine\ORM\EntityRepository;

class AtbusinessRepository extends EntityRepository{
    
    public function findByCompanyAndState(\att\employeeBundle\Entity\Atcompany $company, $state){
        
        $business = $this->findOneBy([
                'company' => $company,
                'state' => $state
            ]);
        return $business;
    }
    
    public function getStateandCompany(){
        
        $qb = $this->createQueryBuilder('b');
        $qb->select('b.state')
            ->addSelect('c.description')    
            ->leftJoin('b.company', 'c')     
              ;
        $result = $qb->getQuery()->getScalarResult();
        return $result; //array_map('current', $result);

    }
    
    public function getState(){
        
        $qb = $this->createQueryBuilder('b');
        $qb->select('b.state') 
            ->groupBy('b.state')   ;
        $result = $qb->getQuery()->getScalarResult();
        return array_map('current', $result);

    }
}
