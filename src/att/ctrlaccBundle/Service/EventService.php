<?php

namespace att\ctrlaccBundle\Service;

use DataDog\PagerBundle\Pagination;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use att\syncBundle\Entity\Atexternsystem;

class EventService {

    protected $em;
    protected $container;

    /**
     * 
     * 
     * @param EntityManager $embiometric
     * @param ContainerInterface $container
     */
    public function __construct(EntityManager $em, ContainerInterface $container) {
        $this->container = $container;
        $this->em = $em;
    }

    public function filters(\Doctrine\ORM\QueryBuilder $qb, $key, $val) {
        switch ($key) {

            case 'e.eventdate':
                if ($val) {
                    $qb->andWhere($qb->expr()->like('a.date', ':date'));
                    $qb->setParameter('date', $val);
                }
                break;

            case 'e.employee':
                if ($val) {
                    $qb->andWhere($qb->expr()->like('e.employee', ':employee'));
                    $qb->setParameter('employee', $val);
                }
                break;

            case 'e.eventtype':
                if ($val) {
                    $qb->andWhere($qb->expr()->like('e.eventtype', ':eventtype'));
                    $qb->setParameter('eventtype', $val);
                }
                break;

            default:
                throw new \Exception("filter not allowed");
        }
    }

    public function pagination(Request $request) {

        $dql = $this->em->getRepository('ctrlaccBundle:Event')->createQueryBuilder('e');


        $options = [
            'sorters' => ['e.eventdate' => 'DESC'],
            'applyFilter' => [$this, 'filters'],
        ];

        $paginator = new Pagination($dql, $request, $options);

        return array("paginator" => $paginator);
    }


    /**
     * 
     * @param type $employee
     * @param type $date
     * @param type $time
     * @param type $type
     * @param type $iddevice
     * @param type $idoriginal
     * @return \att\ctrlaccBundle\Entity\Event
     */
    public function createEvent($employee, $date, $time, $type, Atexternsystem $system, $idoriginal) {


        $device = $this->em->getRepository('ctrlaccBundle:Device')->findOneBy(['system' => $system]);
        $event = new \att\ctrlaccBundle\Entity\Event();
        $event->setEmployee($employee)
                ->setEventdate($date)
                ->setEventtime($time)
                ->setEventtype($type)
                ->setIddevice($device ? $device : null)
                ->setIdoriginal($idoriginal);
        return $event;
    }

    /**
     * 
     * @param type $events
     */
    public function persistEvents($events) {
        $batchSize = 1000;
        $i = 1;
        $errors = array();
        foreach ($events as $event) {
            $i ++;
            $this->em->persist($event);
            if (($i % $batchSize) == 0) {
                try {
                    $this->em->flush();
                    $this->em->clear();
                } catch (\Exception $e) {
                    $errors[] = $e;
                }
            }
            $this->em->flush();
        }
        return ['events' => $events, 'errors_persist' => $errors];
    }

    /**
     * 
     * @param type $event
     */
    public function persistEvent($event) {
        $this->em->persist($event);
        $this->em->flush();
    }
    
    
    

}
