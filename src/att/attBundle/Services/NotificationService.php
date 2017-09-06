<?php

namespace att\attBundle\Services;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManager;
use att\attBundle\Entity\Atabsence;
use DataDog\PagerBundle\Pagination;
use att\employeeBundle\Entity\Atemployee;

class NotificationService {

    protected $em;
    protected $container;

    public function __construct(EntityManager $em, ContainerInterface $container) {
        $this->container = $container;
        $this->em = $em;
    }

    public function filters(\Doctrine\ORM\QueryBuilder $qb, $key, $val) {
        switch ($key) {

            case 'n.date':
                if ($val) {
                    $qb->andWhere($qb->expr()->eq('n.date', ':date'));
                    $qb->setParameter('date', $val);
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
            
            case 'n.fromdate':
                if ($val) {
                    $qb->andWhere($qb->expr()->eq('n.fromdate', ':fromdate'));
                    $qb->setParameter('fromdate', $val);
                }
                break;
                
            case 'n.todate':
                if ($val) {
                    $qb->andWhere($qb->expr()->eq('n.todate', ':todate'));
                    $qb->setParameter('todate', $val);
                }
                break;  
                
            case 't.description':
                if ($val) {
                    $qb->andWhere($qb->expr()->like('t.description', ':description'));
                    $qb->setParameter('description', $val);
                }
                break;    
                
            default:

                throw new \Exception("filter not allowed");
        }
    }

    public function pagination(\Symfony\Component\HttpFoundation\Request $request, Atemployee $employee = null) {
        
        
        
        $dql = $this->em->getRepository('attBundle:Atabsencenotification')->createQueryBuilder('n')
                ->leftJoin('n.employee', 'e')
                ->leftJoin('n.absencetype', 't');
        
        if($employee){
            $dql->andWhere($dql->expr()->eq('n.employee', ':employee'));
            $dql->setParameter('employee', $employee->getId());
            }

        $options = [
            'sorters' => ['n.fromdate' => 'DESC'],
            'applyFilter' => [$this, 'filters'],
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

}
