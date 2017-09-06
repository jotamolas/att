<?php
namespace att\utilBundle\Service;


class GoogleService{
    
    private $token = "AIzaSyCHV9EgrEGcWBc-iWT68S52ALl2uVxDPWA";    
    
    
    public function getToken(){
        return $this->token;
    }
    
    public function geocode($address, $format){
        
        $google_maps_url = "https://maps.googleapis.com/maps/api/geocode/".$format."?address=".urlencode($address)."&key=".$this->token ;
        $google_maps_json = file_get_contents($google_maps_url);
        $google_maps_array = json_decode($google_maps_json);
        
        return $google_maps_array;
        
    }
    
    
    public function processGeocode(Array $google_maps_array){
        
        if(!$google_maps_array){
            return ;
        }
        
        if($google_maps_array->status === 'OK'){
            
            foreach($google_maps_array->results as $address){
                
            }            
        }
    }
    
}