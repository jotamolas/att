<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace att\syncBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;

/**
 * Description of CCMService
 *
 * @author JotaMolas
 */
class CCMAmbaService {
        

    protected $container;
    protected $emccmamba;
        
    public function __construct(EntityManager $emccmamba, ContainerInterface $container) {
        $this->container = $container;

        $this->emccmamba = $emccmamba;
        }
    
        
        
   public function listMailEmployee(Request $request){
       $dql = $this->emccmamba->getRepository('att\syncBundle\Entity\CCMAmba\LisNominam')->createQueryBuilder('e')
               ->leftJoin('e.idesquema', 'esq')
               ->where('e.idestado = 1');
       
       $options = [
            'applyFilter' => [$this, 'employeeFilters'],
         ];
       $paginator = new \DataDog\PagerBundle\Pagination($dql, $request, $options);
       
       return ['paginator' => $paginator];
   }     
   
   
   public function employeeFilters(\Doctrine\ORM\QueryBuilder $qb, $key, $val){
       
       switch ($key){
           
           case 'e.apellido':
                if ($val) {
                $qb->andWhere($qb->expr()->like('e.apellido', ':apellido'));
                $qb->setParameter('apellido', "%$val%");
            }
           break;
           
           
           case 'e.nombre':
                if ($val) {
                $qb->andWhere($qb->expr()->like('e.nombre', ':nombre'));
                $qb->setParameter('nombre', "%$val%");
            }
           break;
           
           case 'e.dni':
                if ($val) {
                $qb->andWhere($qb->expr()->like('e.dni', ':dni'));
                $qb->setParameter('dni', "%$val%");
            }
           break;
           
           case 'e.idmail':
                if ($val) {
                $qb->andWhere($qb->expr()->like('e.idmail', ':idmail'));
                $qb->setParameter('idmail', "%$val%");
            }
           break;
           
           case 'e.idusuario':
                if ($val) {
                $qb->andWhere($qb->expr()->like('e.idusuario', ':idusuario'));
                $qb->setParameter('idusuario', "%$val%");
            }
           break;
           
            case 'esq.deesquema':
                if ($val) {
                $qb->andWhere($qb->expr()->like('esq.deesquema', ':esq'));
                $qb->setParameter('esq', "%$val%");
            }
           break;
           default:   
           throw new \Exception("filter not allowed");
       }
   }
   
   
   
   public function getEmployeeSchema($idfile){
       
       if($idfile){
           $employee = $this->emccmamba->getRepository('att\syncBundle\Entity\CCMAmba\LisNominam')->findOneByLegajo($idfile);
    
                   
       }
       
       if($employee){
           
          return $employee->getIdesquema()->getDeesquema();
       }
   }
   
    public function getEmployeeTime($idfile){
       
       if($idfile){
           $employee = $this->emccmamba->getRepository('att\syncBundle\Entity\CCMAmba\LisNominam')->findOneByDni($idfile);
       }
       
       if($employee){
           return ['in' => $employee->getIdgrupo()->getHeent(),'out' => $employee->getIdgrupo()->getHesal()];
       }
       
   }
}
