<?php

namespace att\attBundle\Repository;

use Doctrine\ORM\EntityRepository;

class AtworkflowRepository extends EntityRepository {

    public function findByCertificatesAndEmployee($entities, \att\attBundle\Entity\Atworkflowtype $workflow) {
        $qb = $this->createQueryBuilder('w');
        $qb->where($qb->expr()->in('w.entityid', ':ids'))
                ->andWhere('w.workflow = :workflow')
                ->setParameter('workflow', $workflow)
                ->setParameter('ids', $entities);
        return $qb->getQuery()->getResult();
    }

}
