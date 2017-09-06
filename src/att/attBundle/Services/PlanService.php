<?php

namespace att\attBundle\Services;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManager;
use DataDog\PagerBundle\Pagination;
use att\attBundle\Entity\Atplan;
use att\attBundle\Entity\Atattendance;
use att\attBundle\Entity\Atplaninconsistency;
use att\employeeBundle\Entity\Atemployee;
use att\attBundle\Entity\Atstateplan;

class PlanService {

    protected $em;
    protected $container;
    protected $mailer;
    protected $template;

    public function __construct(EntityManager $em, ContainerInterface $container) {
        $this->container = $container;
        $this->em = $em;
        $this->mailer = $this->container->get('mailer');
        $this->template = $this->container->get('templating');
    }

    public function evalPlanFromFile($plan) {

        if (!$plan['dni'] || !$plan['date'] || !$plan['intime'] || !$plan['outtime'] || !$plan['state']) {

            return [
                'error' => TRUE,
                'message' =>
                ['FieldBlank' => 'All values must be complete']
            ];
        } else {

            $employee = $this->em->getRepository('employeeBundle:Atemployee')->findByDniToValidatePlan($plan['dni']);
            $date = $this->validateDatePlan(\DateTime::createFromFormat('Ymd', $plan['date']), 'Date Plan');
            $timeIn = $this->validateDatePlan(\DateTime::createFromFormat('Ymd H:i', $plan['date'] . " " . $plan['intime']), 'In Date');
            $timeOut = $this->validateDatePlan(\DateTime::createFromFormat('Ymd H:i', $plan['date'] . " " . $plan['outtime']), 'Out Date');
            $planState = $this->em->getRepository('attBundle:Atstateplan')->findByIdFormatedToPlanService($plan['state']);


            return $this->validatePlanFromFile([
                        'employee' => $employee,
                        'date' => $date,
                        'inplan' => $timeIn,
                        'outplan' => $timeOut,
                        'planState' => $planState
            ]);
        }
    }

    /**
     * 
     * @param type $plans
     * @return type
     */
    public function iteratePlansFromFile($plans) {

        $validatesPlans = [];
        $errors = [];
        foreach ($plans as $key => $plan) {

            $validatePlan = array();
            $error = null;

            $result = $this->evalPlanFromFile($plan);

            if ($result['error']) {
                foreach ($result['message'] as $k => $v) {
                    $error = $error. "The row " . ($key + 1) . " has a error: " . $k . ": " . $v;
                }
            } else {
                foreach ($result['message'] as $k => $v) {
                    $validatePlan[$k] = $v['message'];
                }
            }

            (count($validatePlan)) != 0 ? $validatesPlans[] = $validatePlan : null;
            ($error) ? $errors[] = $error : null;
        }

        return [
            'validatesPlans' => $validatesPlans,
            'errors' => $errors
        ];
    }

    /**
     * 
     * @param type $date
     * @param type $value
     * @return type
     */
    public function validateDatePlan($date, $value) {

        if ($date) {
            return [
                'error' => FALSE,
                'message' => $date
            ];
        } else {
            return [
                'error' => TRUE,
                'message' => "The " . $value . " could not be determined, verify the format."
            ];
        }
    }

    /**
     * 
     * @param type $plan
     * @return type
     */
    public function validatePlanFromFile($plan) {

        $errors = false;

        foreach ($plan as $key => $value) {
            ($value['error']) ? $errors[$key] = $value['message'] : true;
        }

        if ($errors) {
            return [
                'error' => TRUE,
                'message' => $errors
            ];
        } else {
            return [
                'error' => FALSE,
                'message' => $plan
            ];
        }
    }

    /**
     * 
     * @param Atattendance $att
     * @param Atplan $plan
     * @param type $obs
     */
    public function createPlanInconsistency(Atattendance $att, Atplan $plan, $obs) {

        $exp = new Atplaninconsistency();
        $exp->setAtt($att)->setPlan($plan)->setObs($obs);
        $this->em->persist($exp);
        $this->em->flush();
    }

    /**
     * 
     * @param type $plans
     * @return type
     */
    public function createOrUpdatePlanFromFile($plans) {

        $errors = array();
        $entities = array();

        foreach ($plans as $plan) {

            $qb = $this->em->getRepository('attBundle:Atplan')->createQueryBuilder('p')
                    ->where('p.date = :date')
                    ->andWhere('p.employee = :employee')
                    ->setParameter('date', $plan['date'])
                    ->setParameter('employee', $plan['employee']);

            $entityRs = $qb->getQuery()->getResult();

            if ($entityRs) {
                $entityRs->setInplan($plan['inplan'])
                        ->setOutplan($plan['outplan'])
                        ->setStateplan($plan['planState'])
                        ->setHsworkplan(date_diff($plan['outplan'], $plan['inplan'])->format('%h:%i'));

                $this->em->flush();
                $entities[] = (string) $entityRs;
            } else {


                $entity = new \att\attBundle\Entity\Atplan();

                $entity->setEmployee($plan['employee']);
                $entity->setDate($plan['date']);
                $entity->setInplan($plan['inplan']);
                $entity->setOutplan($plan['outplan']);
                $entity->setStateplan($plan['planState']);
                $entity->setHsworkplan(date_diff($plan['outplan'], $plan['inplan'])->format('%h:%i'));


                $validationErrors = $this->container->get('validator')->validate($entity);

                if (count($validationErrors) > 0) {

                    $errors[] = $validationErrors . " " . $entity->getEmployee()->getDni() . " | " . $entity->getDate()->format('Y.m.d');
                } else {

                    $this->em->persist($entity);
                    $this->em->flush();

                    $entities [] = (string) $entity;
                }
            }
        }

        return [
            'errors' => $errors,
            'entities' => $entities
        ];
    }

    /**
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return type
     */
    public function getAllPlans(\Symfony\Component\HttpFoundation\Request $request) {

        $dql = $this->em->getRepository('attBundle:Atplan')->createQueryBuilder('p')
                        ->addSelect('e')->addSelect('sp')
                        ->leftJoin('p.employee', 'e')->leftJoin('p.stateplan', 'sp');


        $options = [
            'sorters' => ['p.date' => 'DESC'],
            'applyFilter' => [$this, 'planFilters'],
        ];

        $states = [
            Pagination::$filterAny => 'Any',
            'Presente' => 'Presente',
            'Ausente' => 'Ausente',
            'Franco' => 'Franco'
        ];

        $paginator = new \DataDog\PagerBundle\Pagination($dql, $request, $options);

        return array("paginator" => $paginator, "states" => $states);
    }

    /**
     * 
     * @param \Doctrine\ORM\QueryBuilder $qb
     * @param type $key
     * @param type $val
     * @throws \Exception
     */
    public function planFilters(\Doctrine\ORM\QueryBuilder $qb, $key, $val) {
        switch ($key) {

            case 'p.date':
                if ($val) {
                    $qb->andWhere($qb->expr()->eq('p.date', ':date'));
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

            case 'p.inplan':
                if ($val) {
                    $qb->andWhere($qb->expr()->like('p.inplan', ':plan'));
                    $qb->setParameter('plan', "%$val%");
                }
                break;

            case 'p.outplan':
                if ($val) {
                    $qb->andWhere($qb->expr()->like('p.outplan', ':plan'));
                    $qb->setParameter('plan', "%$val%");
                }
                break;

            case 'p.hsworkplan':
                if ($val) {
                    $qb->andWhere($qb->expr()->like('p.hsworkplan', ':hs'));
                    $qb->setParameter('hs', "%$val%");
                }
                break;

            case 'sp.description':

                $qb->andWhere($qb->expr()->eq('sp.description', ':description'));
                $qb->setParameter('description', $val);
                break;


            default:

                throw new \Exception("filter not allowed");
        }
    }

    /**
     * 
     * @return boolean
     */
    public function checkPlanForTomorrow() {


        $date = new \DateTime('tomorrow');
        $plans = $this->findPlansForDate($date);
        (count($plans) > 0 ? $result = TRUE : $result = FALSE);

        return $result;
    }

    /**
     * 
     * @param type $stringDate
     * @return string
     */
    public function checkPlanFromStringDate($stringDate) {

        $date = \DateTime::createFromFormat('Ymd', $stringDate);

        if ($date) {
            $plans = $this->em->getRepository('attBundle:Atplan')->findByDate($date);
            (count($plans) > 0 ?
                            $result = ['status' => 'ok', 'message' => count($plans) . ' plans are recorded by the due date ' . $date->format('Y-m-d'), 'qplans' => count($plans)] :
                            $result = ['status' => 'error', 'message' => 'Not registered plans by the due date are found ' . $date->format('Y-m-d'), 'qplans' => count($plans)]);
        } else {
            $result = ['status' => 'error', 'message' => 'The date format entered is not required'];
        }

        return $result;
    }

    /**
     * 
     * @return type
     */
    public function sendWarningMailNotification() {

        $msg = \Swift_Message::newInstance()
                ->setSubject('Attendance Workflow - Plan Warning')
                ->setFrom('att@tasksolutions.com.ar')
                ->setTo('jmolas@hotmail.com')
                ->setBody($this->template->render('attBundle:Planification:warning.mail.txt.twig'));
        return $this->mailer->send($msg);
    }

    /**
     * 
     * @param type $data
     * @return type
     */
    public function processPlansForm($data) {

        $plansUpdate = [];
        $plansNew = [];

        $period = $this->container->get('util.date.service')->getIterateDayPeriod(\DateTime::createFromFormat('Y-m-d', $data['datefrom']), \DateTime::createFromFormat('Y-m-d', $data['dateto']));

        foreach ($period as $date) {

            foreach ($data['employee'] as $emp) {

                $plan = $this->em->getRepository('attBundle:Atplan')->findOneBy(['employee' => $data['employee'], 'date' => $date]);

                if ($plan) {
                    $plan = $this->setPlan($plan, NULL, NULL, \DateTime::createFromFormat('Y-m-d H:i', $date->format('Y-m-d') . " " . $data['inplan']), \DateTime::createFromFormat('Y-m-d H:i', $date->format('Y-m-d') . " " . $data['outplan']), $this->em->getRepository('attBundle:Atstateplan')->find($data['stateplan']));

                    $plansUpdate [] = $plan;
                } else {

                    $planNew = $this->setPlan(new \att\attBundle\Entity\Atplan(), $date, $this->em->getRepository('employeeBundle:Atemployee')->find($emp), \DateTime::createFromFormat('Y-m-d H:i', $date->format('Y-m-d') . " " . $data['inplan']), \DateTime::createFromFormat('Y-m-d H:i', $date->format('Y-m-d') . " " . $data['outplan']), $this->em->getRepository('attBundle:Atstateplan')->find($data['stateplan']));
                    $plansNew [] = $planNew;
                }
            }
        }
        $updated = $this->updatePlans($plansUpdate);
        $persisted = $this->persistPlans($plansNew);

        return ['plansUpdate' => $updated['plans'], 'plansNew' => $persisted['plans'], 'errors_persist' => array_merge($persisted['errors_persist'], $updated['errors_persist'])];
    }

    /**
     * 
     * @param type $data
     */
    public function processPlansSchemaForm($data) {

        $begin = \DateTime::createFromFormat('Y-m-d', $data['datefrom']);
        $end = \DateTime::createFromFormat('Y-m-d', $data['dateto']);
        $empls = $data['employee'];


        $massiveplans = [];
        $errors = [];
        $contracts = [];


        foreach ($empls as $emp) {
            $employee = $this->em->getRepository('employeeBundle:Atemployee')->find($emp);
            foreach ($employee->getContracts() as $contract) {
                $contracts[] = $contract;
            }
        }

        foreach ($contracts as $contract) {
            $plans = $this->createPlanFromSchema($contract, $begin, $end);
            if ($plans['status'] === 'ok') {
                foreach ($plans['plans'] as $plan) {
                    $massiveplans[] = $plan;
                }
            } else {
                $errors[] = $plans['message'];
            }
        }

        if ($massiveplans) {
            $result = $this->persistPlans($massiveplans);
            return [
                'plan_persisted' => $result['plans'],
                'plan_persist_error' => $result['errors_persist'],
                'plan_process_errors' => $errors
            ];
        } else {
            return [
                'plan_persisted' => [],
                'plan_persist_error' =>[],
                'plan_process_errors' => $errors
                    ];
        }
    }

    /**
     * 
     * @param type $plans
     * @return type
     */
    public function updatePlans($plans) {
        $batchSize = 20;
        $i = 1;
        $errors = array();

        foreach ($plans as $plan) {
            $i ++;
            $this->em->merge($plan);
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
        return ['plans' => $plans, 'errors_persist' => $errors];
    }

    /**
     * 
     * @param type $plans
     * @return type
     */
    public function persistPlans($plans) {
        $batchSize = 20;
        $i = 1;
        $errors = array();
        foreach ($plans as $plan) {
            $i ++;
            $this->em->persist($plan);
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
        return ['plans' => $plans, 'errors_persist' => $errors];
    }

    /**
     * 
     * @param Atplan $plan
     * @param \DateTime $date
     * @param Atemployee $employee
     * @param \DateTime $inplan
     * @param \DateTime $outplan
     * @param Atstateplan $stateplan
     * @return Atplan
     */
    public function setPlan(Atplan $plan, \DateTime $date = null, Atemployee $employee = null, \DateTime $inplan = null, \DateTime $outplan = null, Atstateplan $stateplan = null) {

        $date ? $plan->setDate($date) : NULL;
        $employee ? $plan->setEmployee($employee) : NULL;
        $inplan ? $plan->setInplan($inplan) : NULL;
        $outplan ? $plan->setOutplan($outplan) : NULL;
        $stateplan ? $plan->setStateplan($stateplan) : NULL;
        $plan->getOutplan() < $plan->getInplan() ? $plan->setOutPlan($plan->getOutplan()->modify('+1 Day')) : NULL;
        $plan->getInplan() && $plan->getOutplan() ? $plan->setHsworkplan(date_diff($plan->getOutplan(), $plan->getInplan())->format('%h:%i')) : NULL;

        return $plan;
    }

    /**
     * 
     * @param \att\employeeBundle\Entity\Atcontract $contract
     * @param \DateTime $start_date
     * @param \DateTime $end_date
     */
    public function createPlanFromSchema(\att\employeeBundle\Entity\Atcontract $contract, \DateTime $start_date, \DateTime $end_date) {
        
        $plans = [];
        $period = $this->container->get('util.date.service')->getIterateDayPeriod($start_date, $end_date);
        $restdays = $contract->getRestDays();

        if ($contract->getRestDays() && $contract->getInTime() && $contract->getOutTime() && $contract->getStatus()->getDescription() == 'Active') {

            foreach ($period as $dt) {
                $plan = $this->setPlan(new \att\attBundle\Entity\Atplan(), $dt, $contract->getEmployee(), $contract->getInTime(), $contract->getOutTime());
                foreach ($restdays as $restday) {
                    ($dt->format('l') == $restday->getDescription()) ?
                                    $plan->setStateplan($this->em->getRepository('attBundle:Atstateplan')->find(3)) :
                                    $plan->setStateplan($this->em->getRepository('attBundle:Atstateplan')->find(1));
                }

                $plans[] = $plan;
            }

            return [
                'status' => 'ok',
                'plans' => $plans
            ];
        } else {


            return[
                'status' => 'error',
                'message' => 'Contract ' . $contract->getFileNumber() . ' has no registered schema data or not Active'
            ];
        }
    }
    
    
    public function validateFileHeader($header){
        
        $result = array_diff(['dni','date','intime','outtime','state'],$header);
        if(count($result)> 0){
            $message = "The file not contain this fields : ";
            foreach ($result as $field) {
                $message .= " ".$field." ";
            }
            return[
                'error' => true,
                'message' => $message
            ];
        }
        return ['error' => false];
    }
}
