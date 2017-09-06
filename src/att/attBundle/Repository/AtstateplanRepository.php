<?php


namespace att\attBundle\Repository;

use Doctrine\ORM\EntityRepository;

class AtstateplanRepository extends EntityRepository {
    
       public function findByIdFormatedToPlanService($id){
       
       $planState = $this->find($id);
       
       if($planState){
           return [
           'error' => FALSE,
           'message' => $planState
       ];
       }else{
           return [
           'error' => TRUE,
           'message' => "State Plan selected from Employee not found."
       ];
       }
   }
    
}
