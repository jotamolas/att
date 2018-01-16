<?php

namespace att\employeeBundle\Repository;

use Doctrine\ORM\EntityRepository;

class AtcontractRepository extends EntityRepository {

    public function findAllContractToSync() {

        $qb = $this->createQueryBuilder('c');
        $qb->select('e.dni')
                ->addSelect('c.file_number')
                ->addSelect('comp.description')
                ->leftJoin('c.employee', 'e')
                ->leftJoin('c.business', 'b')
                ->leftJoin('b.company', 'comp');

        return $qb->getQuery()->getResult();
    }

    public function getByTerm($term) {
        $qb = $this->createQueryBuilder('c');
        $qb->select('c.id, e.dni, e.name, e.surname, b.description as bus, comp.description as company')
                ->where($qb->expr()->like('e.surname', "'%" . $term . "%'"))
                ->leftJoin('c.employee', 'e')
                ->leftJoin('c.business', 'b')
                ->leftJoin('b.company', 'comp');
        return $qb->getQuery()->getResult();
    }


    public function getActiveContract($id){
        $qb = $this->createQueryBuilder('c');
        $qb
           ->leftJoin('c.status', 's')
           ->where($qb->expr()->eq('c.employee',$id))
           ->andWhere($qb->expr()->eq('s.description', "'Active'"));
        return $qb->getQuery()->getResult(); 
    }

}
