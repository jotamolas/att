<?php

namespace att\attBundle\Services;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManager;
use DataDog\PagerBundle\Pagination;
use att\employeeBundle\Entity\Atemployee;
use att\attBundle\Entity\Atabsence;
class AbsenceService {

    protected $em;
    protected $container;

    public function __construct(EntityManager $em, ContainerInterface $container) {
        $this->container = $container;
        $this->em = $em;
    }

    public function absenceFilters(\Doctrine\ORM\QueryBuilder $qb, $key, $val) {
        switch ($key) {

            case 'p.date':
                if ($val) {
                    $qb->andWhere($qb->expr()->eq('p.date', ':date'));
                    $qb->setParameter('date', $val);
                }
                break;

            case 'e.dni':
                if ($val) {
                    $qb->andWhere($qb->expr()->like('e.dni', ':dni'));
                    $qb->setParameter('dni', "%$val%");
                }
                break;
                
            case 'e.name':
                if ($val) {
                    $qb->andWhere($qb->expr()->like('e.name', ':name'));
                    $qb->setParameter('name', "%$val%");
                }
                break;

            case 'e.surname':
                if ($val) {
                    $qb->andWhere($qb->expr()->like('e.surname', ':surname'));
                    $qb->setParameter('surname', "%$val%");
                }
                break;

            default:

                throw new \Exception("filter not allowed");
        }
    }

    public function persistAbsence(Atabsence $absence) {

        $this->em->persist($absence);
        $this->em->flush();

        return ($absence);
    }

    public function paginationAbsence(\Symfony\Component\HttpFoundation\Request $request) {

        $dql = $this->em->getRepository('attBundle:Atabsence')->createQueryBuilder('abs')
                ->leftJoin('abs.attendance', 'a')
                ->leftJoin('a.plan', 'p')
                ->leftJoin('p.employee', 'e');


        $options = [
            'sorters' => ['p.date' => 'DESC'],
            'applyFilter' => [$this, 'absenceFilters'],
        ];


        $paginator = new Pagination($dql, $request, $options);

        return array("paginator" => $paginator);
    }

    public function searchAbsenceNotificactions(Atabsence $absence) {

        $employee = $absence->getAttendance()->getPlan()->getEmployee();
        $plan_date = $absence->getAttendance()->getPlan()->getDate();
        $return = null;
        $notifications = $this->em->getRepository('attBundle:Atabsencenotification')->findByEmployee($employee);

        foreach ($notifications as $notification) {
            $period = $this->container->get('util.date.service')->getIterateDayPeriod($notification->getFromdate(), $notification->getTodate());
            foreach ($period as $date) {

                if ($plan_date == $date) {

                    $return = $notification;
                }
            }
        }
        if ($return) {
            return $return;
        } else {
            return NULL;
        }
    }

    public function searchWorkflowCertification(Atabsence $absence) {

        $plan_date = $absence->getAttendance()->getPlan()->getDate();
        
        $return = null;
        $workflows_certification = $this->em->getRepository('attBundle:Atworkflow')->findByCertificatesAndEmployee($this->em->getRepository('attBundle:Atcertificate')->findByEmployee($absence->getAttendance()->getPlan()->getEmployee()), $this->em->getRepository('attBundle:Atworkflowtype')->find('wf.certificate'));
        foreach ($workflows_certification as $wkf) {
            $certificate = $this->em->getRepository('attBundle:Atcertificate')->find($wkf->getEntityid());
            $period = $this->container->get('util.date.service')->getIterateDayPeriod($certificate->getDatefrom(), $certificate->getDateto());
            
                foreach ($period as $date) {
                $plan_date != $date ? 
                        null : 
                        $return = [
                            'workflow_certification' => $wkf,
                            'certificate' => $certificate->setDateto($certificate->getDateto()->modify('- 1 Day'))
                        ]; ///REVISAR ESTO, ASI LO PUDE HACER NOMAS 
            }
        }
        return $return;
    }

    public function searchWorkleave(Atabsence $absence) {
        //Optimizar esta busqueda luego remitirar 
        $workleaves = $this->em->getRepository('attBundle:Atworkleave')->findBy([
            'employee' => $absence->getAttendance()->getPlan()->getEmployee()
        ]);
        $return = null;
        foreach ($workleaves as $workleave){
            $period = $this->container->get('util.date.service')->getIterateDayPeriod($workleave->getDateFrom(), $workleave->getDateTo());
            foreach($period as $date){
                $absence->getAttendance()->getPlan()->getDate() == $date ?
                        $return = $workleave : 
                        null;
            }
            return $return;
        }
        
    }
    
    public function getMedicalOrder(Atabsence $absence){
        $medical_orders = $this->em->getRepository('medicalsrvBundle:Atmedicalorder')->findBy(['employee' => $absence->getAttendance()->getPlan()->getEmployee()]);
        $return = null;
        foreach ($medical_orders as $order){
            $visits = $order->getMedicalvisits();
            foreach ($visits as $v){
                $period = $this->container->get('util.date.service')->getIterateDayPeriod($v->getRestdatefrom(), $v->getRestdateto());
                foreach($period as $date){
                  $absence->getAttendance()->getPlan()->getDate() == $date ?
                          $return = $order : 
                          NULL;
                }
            }       
        }
        return $return;
    }

}
