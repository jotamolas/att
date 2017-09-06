<?php
namespace att\attBundle\Services;
use Symfony\Component\DependencyInjection\ContainerInterface;

class SpreadsheetService {
    
    protected $container;
    public function __construct(ContainerInterface $container) {
        $this->container = $container;               
    } 
    
    
    /**
     * 
     * @param type $filename
     */
    public function readSpreadsheet($filename) {
        return \PHPExcel_IOFactory::load($filename);         
    }
    
    
    /**
     * 
     * @param type $filename
     */
    public function readPlanExcel($filename){
               
        $isValid = 0;
        $sheets = $this->getListWorkSheets($filename);
        
        foreach ($sheets as $sheet) {
            if($sheet == 'plan') {
                $isValid ++;  
            }
        }
        
        if ($isValid == 1 ){
            $error = FALSE;
            $message = $this->readPlanSheet($filename);
            
            
        }else{
            $error = TRUE;
            $message = "The workbook should contain only one worksheet called 'plan'";
        }
        
        return [
            'error' => $error,
            'message' => $message
        ];
        
    }
    
    
    /**
     * 
     * @param type $filename
     */
    
    public function readPlanSheet($filename){
        
        $objType = $this->getSpredsheetType($filename);
        $objReader = $this->createObjReader($objType); 
        
        
        $objPHPExcel = $objReader->load($filename); 
        $planSheet = $objPHPExcel->getActiveSheet();
        
        return $planSheet->toArray();
        
    }


    /**
     * 
     * @param type $objType
     * @return type
     */
    public function createObjReader($objType) {
        $objReader = \PHPExcel_IOFactory::createReader($objType);
        return $objReader;
        
    }
    
    /**
     * 
     * @param type $file
     * @return type
     */
    public function getSpredsheetType($file){
        return \PHPExcel_IOFactory::identify($file);
    }
    
    /**
     * 
     * @param type $filename
     * @return type
     */
    public function getListWorkSheets($filename){
        $objType = $this->getSpredsheetType($filename);
        $objReader = $this->createObjReader($objType);
        return $objReader->listWorksheetNames($filename);
    }
    
    
    
    
    
}