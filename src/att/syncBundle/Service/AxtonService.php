<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace att\syncBundle\Service;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManager;
/**
 * Description of AxtonService
 *
 * @author jorge.molas
 */
class AxtonService {
    
    
        protected $emctrlacc;
        protected $embiometric;
        protected $container;
        protected $em;
        protected $emaxton;


    /**
     * 
     * @param EntityManager $emaxton
     * @param EntityManager $em
     * @param EntityManager $emctrlacc
     * @param EntityManager $embiometric
     * @param ContainerInterface $container
     */
    public function __construct(EntityManager $emaxton, EntityManager $em, EntityManager $emctrlacc, EntityManager $embiometric , ContainerInterface $container) {
        $this->container = $container;
        $this->emctrlacc = $emctrlacc;
        $this->embiometric = $embiometric;
        $this->em = $em;
        $this->emaxton = $emaxton;
    }
    
    public function getEmployeeDataFromAxton ($fromdate, $todate = null){
        
        $axtonWS = $this->container->get('besimple.soap.client.axton'); 
        $result = $axtonWS->__soapCall(
                "GetEmpleadosString",
                [ "GetEmpleadosString" => [ 
                                            "desdeing"  => $fromdate, 
                                            "hastaing"  => $todate,
                                            'token' => $this->container->getParameter('sync.axton.token')
                                        ]
                ]);
        $xml_object = new \SimpleXMLElement($result->GetEmpleadosStringResult);
        $xml_object->registerXPathNamespace('d', 'urn:schemas-microsoft-com:xml-diffgram-v1');
        $rs = $xml_object->xpath('//Table');
        $employees = array();
        
        if (!$this->container->hasParameter('axton.company')){
            return $rs;
        }else{
            foreach($rs as $employee){
                ((strpos(trim($employee->Empresa->__toString()),$this->container->getParameter('axton.company'))) !== FALSE ? $employees[] = $employee : false);
                
            }
            return $employees;
        }        
    }

    /*public function exportEventsToAxton($day){
        
        $date = date_create_from_format('Ymd', $day);
        $atts = $this->container->get('ctrlacc.attendance.service')->getAttendancesFromDay($date);
        $result = array();
        if($atts){
            foreach ($atts as $att){
                $employee = $this->container->get('employee.service')->getEmployeeByIdFileOrDni($att->getEmployee());
                if($employee){
                   $result[] = [
                       'LegNumero' => $employee->getLegajo(),
                                      'InOut' => '1',
                                      'Fecha' => $att->getInEvent()                                
                                    ];
                   ($att->getOutEvent() ?
                                        $result[] = [
                                            'LegNumero' => $employee->getLegajo(),
                                            'InOut' => '0',
                                            'Fecha' => $att->getOutEvent()                                
                                        ]:
                                        NULL);       
                }
            }
        }
       
       return $result;
    }*/
    
    public function syncEmployee(){
        $employees = null;
        $emps = $this->getEmployeeDataFromAxton('2008-01-01');
        foreach($emps as $emp){
            $axtonEmployee = new \att\syncBundle\Entity\Axton\Employees();
            $axtonEmployee->setDni(trim($emp->DNI->__toString()));
            $axtonEmployee->setOperacion(trim($emp->Operacion->__toString()));
            $axtonEmployee->setEmpresa(trim($emp->Empresa->__toString()));
            $axtonEmployee->setLegnumero(trim($emp->Legnumero->__toString()));
            $axtonEmployee->setApellido(trim($emp->Apellido->__toString()));
            $axtonEmployee->setNombre(trim($emp->Nombre->__toString()));
            $axtonEmployee->setCuil(trim($emp->CUIL->__toString()));
            $axtonEmployee->setFechaNacimiento(trim($emp->Fecha_nacimiento->__toString()));
            $axtonEmployee->setSexo(trim($emp->Sexo->__toString()));
            $axtonEmployee->setDomicilio(trim($emp->Domicilio->__toString()));
            $axtonEmployee->setLocalidad(trim($emp->Localidad->__toString()));
            $axtonEmployee->setSector(trim($emp->Sector->__toString()));
            $axtonEmployee->setGerencia(trim($emp->Gerencia->__toString()));
            $axtonEmployee->setLugarpago(trim($emp->LugarPago->__toString()));
            $axtonEmployee->setFechaIngreso(trim($emp->Fecha_ingreso->__toString()));
            $axtonEmployee->setFechaBaja(trim($emp->Fecha_baja->__toString()));
            $axtonEmployee->setCbu(trim($emp->CBU->__toString()));
            $axtonEmployee->setTipoCuenta(trim($emp->Tipo_cuenta->__toString()));
            $axtonEmployee->setNroCuenta(trim($emp->Nro_cuenta->__toString()));
            $axtonEmployee->setCategoria(trim($emp->Categoria->__toString()));
            $axtonEmployee->setMotivoEgreso(trim($emp->Motivo_egreso->__toString()));
            $axtonEmployee->setSectorred1(trim($emp->SectorRed1->__toString()));
            $axtonEmployee->setGerenciared1(trim($emp->GerenciaRed1->__toString()));
            $axtonEmployee->setCategoriared1(trim($emp->CategoriaRed1->__toString()));
            $axtonEmployee->setProvincia(trim( $emp->Provincia->__toString()));
            $axtonEmployee->setCalificacion(trim($emp->Calificacion->__toString()));
            $axtonEmployee->setPresupuesto(trim($emp->Presupuesto->__toString()));
            $employees[] = $axtonEmployee;        
        }
        
        
        $connection = $this->emaxton->getConnection();
        $plataform = $connection->getDatabasePlatform();
        $connection->executeUpdate($plataform->getTruncateTableSQL('employees'));
        
        $batchSize = 20;
        $i = 1;


        foreach ($employees as $employee) {            
            $i ++;
            $this->emaxton->persist($employee);
            if (($i % $batchSize) == 0){
                $this->emaxton->flush();
                $this->emaxton->clear();
                }            
            }
            
         
            
            return $employees;
            
    }

    public function getEmpFromDb($dni){
        $result = $this->emaxton->getRepository('att\syncBundle\Entity\Axton\Employees')->findByDni($dni);
        return $result;
    }
        
    public function getNewEmployeeToSync($dni){
        
        $qb = $this->emaxton->getRepository('att\syncBundle\Entity\Axton\Employees')->createQueryBuilder('e');
        $qb->where($qb->expr()->notIn('e.dni', ":dni"))
                ->setParameter("dni", $dni)
                ->addGroupBy('e.dni');
                        
        return $qb->getQuery()->getResult();
    }
    
    public function getExistEmployeeToSync($dni){
        
        $qb = $this->emaxton->getRepository('att\syncBundle\Entity\Axton\Employees')->createQueryBuilder('e');
        $qb->where($qb->expr()->in('e.dni', ":dni"))
                ->setParameter("dni", $dni)
                ->addGroupBy('e.dni');
                        
        return $qb->getQuery()->getResult();
    }
    
    
    
    
    
    
    /**
     * sin usar
     * @param type $parameters
     * @return type
     */
    public function getNewContract($parameters){
        $qb = $this->emaxton->getRepository('att\syncBundle\Entity\Axton\Employees')->createQueryBuilder('e');
        
        foreach($parameters as $parameter){
            
            $qb->orWhere(
                    $qb->expr()->andX(
                            $qb->expr()->notIn('e.dni', ":dni"),
                            $qb->expr()->notIn('e.legnumero', ":legajo"),
                            $qb->expr()->notIn('e.empresa', ":empresa")
                            ));
                    

            $qb
               ->setParameter("dni", $parameter['dni'])
               ->setParameter("legajo", $parameter['file_number'])
               ->setParameter("empresa", $parameter['description']);
        }
        
        return $qb->getQuery()->getResult();        
    }    
    
    public function getEmpresas($in = null , $notin = null){
        
        $empresas = $this->emaxton->getRepository('att\syncBundle\Entity\Axton\Employees')->findEmpresas($in,$notin);
        return $empresas;
    }
    
    public function getNegocios($in = null , $notin = null){
        
        $negocios = $this->emaxton->getRepository('att\syncBundle\Entity\Axton\Employees')->findSQLNegocios($in,$notin);
        return $negocios;
        
    }
}
