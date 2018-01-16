<?php

namespace att\attBundle\Services;

use att\attBundle\Entity\Atattendance;
use att\ctrlaccBundle\Entity\Attendance;
use att\attBundle\Entity\Atabsence;
use DataDog\PagerBundle\Pagination;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AttendanceService {

    protected $em;
    protected $container;
    
     /**
     * 
     * @param EntityManager $em
     * @param ContainerInterface $container
     */
     
    public function __construct(EntityManager $em, ContainerInterface $container) {
        $this->container = $container;
        $this->em = $em;        
    } 
    
    
    public function attFilters(\Doctrine\ORM\QueryBuilder $qb, $key, $val){
        switch ($key) {

        case 'p.date':
            if($val){
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
        
        case 'a.inatt':
            if ($val) {
                $qb->andWhere($qb->expr()->like('a.inatt', ':in'));
                $qb->setParameter('in', "%$val%");
            }
            break;
            
        case 'a.outatt':
            if ($val) {
                $qb->andWhere($qb->expr()->like('a.outatt', ':out'));
                $qb->setParameter('out', "%$val%");
            }
            break;    
            
            
        case 'a.hsworked':
            if ($val) {
                $qb->andWhere($qb->expr()->like('a.hsworked', ':hs'));
                $qb->setParameter('hs', "%$val%");
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
        
        case 'sa.description':
           
            $qb->andWhere($qb->expr()->eq('sa.description', ':description'));
            $qb->setParameter('description', $val);
            break;
            
            
        default:
   
            throw new \Exception("filter not allowed");

        }
    }
    
    public function createAttendace($plan){
        
        $att = new Atattendance();
        $att->setPlan($plan);
        
        $validationErrors = $this->container->get('validator')->validate($att);
        
        if(count($validationErrors) == 0){
            return [
                'status' => 'ok',
                'entity'  => $att
                    ];
            
        }else{
            return [
                'status' => 'error',
                'error' => $validationErrors
                 ];
        }
        
    }
    
    
    
    /**
     * This method find Plans registred for day.
     * @return type
     */
    public function firstStepCreateAttendance($day){

        /* recupera la jornada laboral establecida a partir del dia enviado*/
        $dates = $this->container->get('util.workingday.service')->checkWorkingDay($day);
        
        /* IMPORTANTE recupera los PLANES que jhay para la fecha evaluada*/
        $plans = $this->em->getRepository('attBundle:Atplan')->findByDate($dates['start']);
        
        if(count($plans)>0){
            /* si hay Planes devuelve un ok y los planes recuperados */
            return [
                'message' => $plans, 
                'status' => 'ok'
            ];

        }else{
            /* si no encuentra planes devuelve un error e informa que no hay planes*/
            return [
                'status' => 'error', 
                'message' => 'No plans were found'
            ];
        }

    }
    
    public function getAllAttendance(\Symfony\Component\HttpFoundation\Request $request){
        
        $dql = $this->em->getRepository('attBundle:Atattendance')->createQueryBuilder('a')
                 ->leftJoin('a.plan', 'p')
                 ->leftJoin('a.stateattendance', 'sa')
                 ->leftJoin('p.employee', 'e')
                 ->leftJoin('p.stateplan', 'sp');

        
        $options = [
             'sorters' => ['p.date'=> 'DESC'],
             'applyFilter' => [$this, 'attFilters'],
         ];
        
        $states = [            
        Pagination::$filterAny => 'Any',
        'Presente' => 'Presente',
        'Ausente' => 'Ausente',
        'Franco' => 'Franco'     
        ]; 
         
        $paginator = new Pagination($dql, $request, $options);
        
        return array ("paginator"=> $paginator, "states" => $states);
    }
    
    public function secondStepCreateAttendance($plans){
        /* declaro dos array, uno para alojar las asistencias que podria crear y otro con los errores de validación */
        $atts = array();
        $errors_validation = array();
        
        /* se navega por los planes, por cada plan se ejecuta el metodo createAttendance para crear la asistencia */
        foreach($plans as $plan){            
           $result = $this->createAttendace($plan);
           /* si createAttendance no devolvio un error se agregan las asistencias a un array, si devolvio un error se agregar a los errores de validacion */ 
           ($result['status'] == 'ok' ? 
            $atts[] = $result['entity'] :
            $errors_validation[] = $result['error'] );
        }
        
        /* 
        * si existen asistencias las persisto 
        * se devuelve un estado ok con el mensaje las 
        */
        //dump($errors_validation);
        if(count($atts)>0){
            $result = $this->persistAttendances($atts);
            return [
                    'status' => 'ok',
                    'operation' => $this->container->get('translator')->trans('Make'),
                    'message' => $this->container->get('translator')->trans('One or more attendances are saved'),
                    'atts' => $result['atts'],
                    'errors_validation' => $errors_validation,
                    'errors_persist' => isset($result['errors']) ? $result['errors'] : NULL
               ];
        }else{
           return [
                    'status' => 'error',
                    'operation' => $this->container->get('translator')->trans('Make'),
                    'message' => $this->container->get('translator')->trans('One or more attendances could not be recorded. See reported errors.'),
                    'atts' => NULL,
                    'errors_persist' => NULL,
                    'errors_validation' => $errors_validation
               ]; 
        }
        
    }    

    
    public function persistAttendances($atts){
           
        $batchSize = 20;
        $i = 1;
        $errors = array();
            
        
        foreach ($atts as $att) {            
            $i ++;
            $this->em->merge($att);
            if (($i % $batchSize) == 0){
                try{
                    $this->em->flush();
                    $this->em->clear();
                }catch(\Exception $e){
                    $errors[] = $e;
                }
            }
            $this->em->flush();
        }
        return ['atts' => $atts, 'errors_persist' => $errors];
        
    }
    
    public function persistAttendance(Atattendance $att){

        try{
            $this->em->persist($att);
            $this->em->flush();
            return  $att;
        }  catch (\Exception $e){
            return  $e;
        }
    }
    
    //
    //VERR NO ME PROCESA ME AISLA
    
    public function process(Atattendance $att, Attendance $ctrlaccatt = NULL){
        
        //dump($att->getPlan()->getEmployee()->getSurname().' '.$att->getPlan()->getStateplan()->getDescription());
        switch ($att->getPlan()->getStateplan()->getDescription()){
            
            case 'Presente':

                if(!$ctrlaccatt){
                  
                    $rangetime = $att->getPlan()->getInplan()->diff(new \DateTime());
                    
                    if($rangetime->format('%H') >= $this->container->getParameter('att_max_hours_tolerance')){
                        
                        $att->setStateattendance(
                                $this->em
                                ->getRepository('attBundle:Atstateatt')
                                ->findOneByDescription('Ausente'));
                        
                        $this->em->flush();
                        
                        $abs = new Atabsence();
                        $abs->setAttendance($att);
                        
                        $validate = $this->container->get('validator')->validate($abs);
                        
                        
                        
                        if(count($validate)===0){ 
                            
                            /* buscar si hay certificacion para meterle */
                            $wf_search_result = $this->container->get('att.absence.service')->searchWorkflowCertification($abs);
                            $wf_search_result ? $abs->setCertification($wf_search_result['workflow_certification']->getId())->setStatejustif(1) : null;
                            /* buscar si hay una licencia en la fecha y asociarla */
                            $is_it_licensed = $this->container->get('att.absence.service')->searchWorkleave($abs);
                            $is_it_licensed ? $abs->setWorkleave($is_it_licensed) : null;
                            /* buscar si tiene servicios medicos hechos */
                            $is_medical_order = $this->container->get('att.absence.service')->getMedicalOrder($abs);
                            $is_medical_order ? $abs->setMedicalorder($is_medical_order)->setStatejustif(true) : null;
                            dump($is_medical_order);
                            $this->em->persist($abs);
                            $this->em->flush();
                        }
                    }                    
                }else{
                    
                    $att
                    ->setInatt($ctrlaccatt->getInEvent())
                    ->setOutatt($ctrlaccatt->getOutEvent())
                    ->setStateattendance($this->em->getRepository('attBundle:Atstateatt')
                            ->findOneByDescription($att->getPlan()->getStateplan()->getDescription()))
                    ->setHsworked($ctrlaccatt->getHoursWorkedTime());
                    //busco si ya esta como ausente y vino en otro horario, borro la ausencia
                    
                    $this->em->flush();
                    $abs = $att->getAbsence();                    
                    if($abs){
                        $rangetime = $att->getPlan()->getInplan()->diff($att->getInatt());
                        $rangetime->format('%H') >= 1 ?
                        $exp = $this->container->get('att.plan.service')
                                ->createPlanInconsistency($att,
                                        $att->getPlan(),
                                        "There has been a different schedule than planned"):
                            NULL;
                        $this->em->remove($abs);
                        $this->em->flush();
                        
                    }        
                }

                break;
            
            case 'Ausente':
                
                if(!$ctrlaccatt){
                   
                   $att ->setStateattendance($this->em->getRepository('attBundle:Atstateatt')
                            ->findOneByDescription($att->getPlan()->getStateplan()->getDescription()));
                   
                   $this->em->flush();
                   $abs = new Atabsence();
                   $abs->setAttendance($att);
                   $validate = $this->container->get('validator')->validate($abs);
                   if(count($validate)=== 0){   
                            /* buscar si hay certificacion para meterle */
                            $wf_search_result = $this->container->get('att.absence.service')->searchWorkflowCertification($abs);
                            $wf_search_result ? $abs->setCertification($wf_search_result['workflow_certification']->getId())->setStatejustif(1) : null;
                            
                            $is_it_licensed = $this->container->get('att.absence.service')->searchWorkleave($abs);
                            $is_it_licensed ? $abs->setWorkleave($is_it_licensed) : null;
                            
                            $is_medical_order = $this->container->get('att.absence.service')->getMedicalOrder($abs);
                            $is_medical_order ? $abs->setMedicalorder($is_medical_order)->setStatejustif(true) : null;
                            
                            //dump($is_medical_order);
                            $this->em->persist($abs);
                            $this->em->flush();
                        }
                    
                }else{
                    $exp = $this->container->get('att.plan.service')
                                ->createPlanInconsistency($att,
                                        $att->getPlan(),
                                        "There has been recorded assistance when planning an absence");
                    $att->setStateattendance(
                                $this->em
                                ->getRepository('attBundle:Atstateatt')
                                ->findOneByDescription('Presente'))
                        ->setInatt($ctrlaccatt->getInEvent())
                        ->setOutatt($ctrlaccatt->getOutEvent()); 
                    
                }
                break;
            
            case 'Franco':    
                
                if(!$ctrlaccatt){
                   
                   $att ->setStateattendance($this->em->getRepository('attBundle:Atstateatt')
                            ->findOneByDescription($att->getPlan()->getStateplan()->getDescription()));
                   
                   $att->getAbsence() ? $this->em->remove($att->getAbsence()) : NULL; 
                   $this->em->flush();
                   
                    
                }else{
                    $exp = $this->container->get('att.plan.service')
                                ->createPlanInconsistency($att,
                                        $att->getPlan(),
                                        "There has been recorded assistance when planning a day of rest");
                    $att->setStateattendance(
                            $this->em
                                ->getRepository('attBundle:Atstateatt')
                                ->findOneByDescription('Presente'))
                        ->setInatt($ctrlaccatt->getInEvent())
                        ->setOutatt($ctrlaccatt->getOutEvent());  
                    $this->em->flush();
                   
                }
                break;
                
        }
        
        return [
            'att' => $att,
            'abs' => isset($abs) ?  $abs : NULL,
            'exp' => isset($exp) ?  $exp : NULL,
        ];
    }

}
