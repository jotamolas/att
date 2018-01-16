<?php

namespace att\attBundle\Repository;

use Doctrine\ORM\EntityRepository;
use att\employeeBundle\Entity\Atemployee;

class AtattendanceRepository extends EntityRepository {

    public function findByDate(\DateTime $day) {

        $qb = $this->createQueryBuilder('a');
        $qb->addSelect('p')
                ->leftJoin('a.plan', 'p')
                ->where('p.date = :day')
                ->setParameter(':day', $day->format('Y-m-d'));

        return $qb->getQuery()->getResult();
    }

    public function findBetweenDates(\DateTime $from, \DateTime $to) {

        $qb = $this->createQueryBuilder('a');
        $qb->addSelect('p')
                ->leftJoin('a.plan', 'p')
                ->where($qb->expr()->between('p.date', ':from', ':to'))
                ->setParameter('from', $from)
                ->setParameter('to', $to);

        return $qb->getQuery()->getResult();
    }

    public function findBetweenInPlanDates(\DateTime $from, \DateTime $to) {

        $qb = $this->createQueryBuilder('a');
        $qb->addSelect('p')
                ->leftJoin('a.plan', 'p')
                ->where($qb->expr()->between('p.inplan', ':from', ':to'))
                ->setParameter('from', $from)
                ->setParameter('to', $to);
        return $qb->getQuery()->getResult();
    }

    public function resumePresenteeismByDate(\DateTime $from, \DateTime $to) {

        $q = $this->getEntityManager()->createQuery('
                SELECT 
                b.id as business_id,
                b.description as business,
                cia.description as company,  
                SUM(CASE when a.stateattendance = 1 THEN 1 ELSE 0 END) as presents, 
                SUM(CASE when a.stateattendance = 2 THEN 1 ELSE 0 END) as absents, 
                (
                   (SUM(CASE when a.stateattendance = 1 THEN 1 ELSE 0 END)) + 
                   (SUM(CASE when a.stateattendance = 2 THEN 1 ELSE 0 END))   
                )  as planned,
                SUM(CASE when a.stateattendance = 1 THEN 1 ELSE 0 END) / (
                   (SUM(CASE when a.stateattendance = 1 THEN 1 ELSE 0 END)) + 
                   (SUM(CASE when a.stateattendance = 2 THEN 1 ELSE 0 END))   
                ) as pct_presents,
                SUM(CASE when a.stateattendance = 2 THEN 1 ELSE 0 END) / (
                   (SUM(CASE when a.stateattendance = 1 THEN 1 ELSE 0 END)) + 
                   (SUM(CASE when a.stateattendance = 2 THEN 1 ELSE 0 END))   
                ) as pct_absences'
                . ' FROM attBundle:Atattendance a '
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
        /*0dump($q->getSql());
        dump($q->getParameters());
        ;*/
        return $q->getResult();
    }

    public function resumeHsWorkedToPayement(\DateTime $from, \DateTime $to) {
        $q = $this->getEntityManager()->createQuery('
        SELECT

        e.name as NAME,
        e.surname as SURNAME,
        e.dni as ID_PERSON,
        SUM(a.hsworkedtopayment) as HS_PAYMENT,
        SUM(CASE when a.stateattendance = 3 THEN p.hsworkplan ELSE 0 END) as HS_PAYMENT_RESTDAY,
        SUM(CASE when abs.statejustif = true THEN p.hsworkplan ELSE 0 END) as HS_PAYMENT_ABSENCE,
        SUM(CASE when abs.statejustif = false THEN p.hsworkplan ELSE 0 END) as HS_NO_PAYMENT_ABSENCE,
        SUM(CASE when adit.isapproved = true THEN adit.amount ELSE 0 END) as HS_ADITIONAL_50

        FROM attBundle:Atattendance a
        
        LEFT JOIN attBundle:Atplan p WITH p.id = a.plan
        LEFT JOIN attBundle:Atabsence abs WITH abs.attendance = a.id
        LEFT JOIN attBundle:Ataditionalhoursdetail adit WITH adit.attendance = a.id
        LEFT JOIN employeeBundle:Atemployee e WITH e.id = p.employee

        WHERE p.date BETWEEN :datefrom and :dateto
        GROUP BY p.employee'
                );
        $q->setParameter('datefrom', $from);
        $q->setParameter('dateto', $to);
        return $q->getResult();
    }

    public function amountHoursWorkedbyEmployee(DateTime $from, \DateTime $to, Atemployee $employee) {
        
    }

}
