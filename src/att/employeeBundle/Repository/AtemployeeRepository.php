<?php

namespace att\employeeBundle\Repository;

use Doctrine\ORM\EntityRepository;


class AtemployeeRepository extends EntityRepository
{
    
    public function findBySurnameCapitalLetter($letter){
        
        $qb = $this->createQueryBuilder('e');
        $qb->where($qb->expr()->like('e.surname', ':letter'))
           ->setParameter('letter', $letter."%");
        
        return $qb->getQuery()->getResult();
    }
    
    public function findByDniToValidatePlan($value){
        
        $employee = $this->findOneByDni($value);
        
        if($employee){
            return [
                    'error' => FALSE,
                    'message' => $employee
            ];
        }else{
            return [
                'error' => TRUE,
                'message' => "Not found Employee with ID (".$value.") provided"
            ];
        }

    }
    
    
    
    
    
    
    public function getAllDNI(){
        
        $qb = $this->createQueryBuilder('e');
        $qb->select('e.dni');
        return $qb->getQuery()->getResult();
    }
    
    
    public function findByContractStatus($status){
        
        $qb = $this->createQueryBuilder('e');
        $qb->select('e.dni')
                ->leftJoin('e.contracts', 'c')
                ->leftJoin('c.status', 's')
                ->where($qb->expr()->eq('s.description', "'".$status."'"));
        return $qb->getQuery()->getResult();
        
    }
    
    
    public function getByTerm($term){
        $qb = $this->createQueryBuilder('e');
        $qb->select('e.id, e.dni, e.name, e.surname')
                ->where($qb->expr()->like('e.surname', "'%".$term."%'"));
        return $qb->getQuery()->getResult();
    }
    
    public function findBySurnameTerm($term){
        $qb = $this->createQueryBuilder('e');
        $qb->where($qb->expr()->like('e.surname', "'%".$term."%'"));
        return $qb->getQuery()->getResult();
    }
}
