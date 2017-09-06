<?php

namespace att\employeeBundle\Service;


use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CompanyService {
    
    protected $em;
    protected $container;
    
    
    public function __construct(EntityManager $em, ContainerInterface $container) {
        
        $this->em = $em;
        $this->container = $container;
        
    }
    
    public function syncFromAxton(){
        
        $companyDescription = $this->em->getRepository('employeeBundle:Atcompany')->getDescriptions();
        $empresas = $this->container->get('sync.axton.service')->getEmpresas(NULL,$companyDescription);
        $errors_validation = [];
        
        if(!$empresas){
            return ['status' => 'error', 'companies' => [], 'message' => 'There are no new companies to register'];
        } 
        
        foreach ($empresas as $empresa){
            
            $company = new \att\employeeBundle\Entity\Atcompany();
            $company->setDescription($empresa['empresa']);
            $company->setObs("Synchronized from axton");
            $validation = $this->container->get('validator')->validate($company);
            
            if(count($validation)>0){
                $errors_validation[] = $validation;
            }else{
                $this->em->persist($company);
                $this->em->flush();
                
            }
        }
        return ['status' => 'ok' , 'companies' => $empresas, 'message' => count($empresas).'Companies were registered', 'errors_validation' => $errors_validation];
                
    }
}
