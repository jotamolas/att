<?php

namespace att\employeeBundle\Service;


use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;




class BusinessService{
    
    protected $em;
    protected $container;
    
    
    public function __construct(EntityManager $em, ContainerInterface $container) {
        
        $this->em = $em;
        $this->container = $container;
        
    }
    
    
    /**
     * 
     * @param type $company
     * @param type $state
     * @return type
     */
    public function getBusinessByCompanyAndState($company, $state){


       $business = $this->em->getRepository('employeeBundle:Atbusiness')->findOneBy([
                'company' => $this->em->getRepository('employeeBundle:Atcompany')->findOneByDescription($company),
                'state' => $state
            ]);
                    
 
        
        return $business;
    }
    
    
    public function syncFromAxton(){
        
        $businessStates = $this->em->getRepository('employeeBundle:Atbusiness')->getState();
        $companyDescription = $this->em->getRepository('employeeBundle:Atcompany')->getDescriptions();
        
        $axton = $this->container->get('sync.axton.service')->getNegocios(NULL,
                ['companies' => $companyDescription, 'states' => $businessStates]
                 );
        //$axton1 = $this->container->get('sync.axton.service')->getNegocios(NULL, $companyStates);
        
        
        return $axton;
    }
}