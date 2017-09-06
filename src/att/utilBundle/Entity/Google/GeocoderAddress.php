<?php

namespace att\utilBundle\Entity\Google;


class GeocoderAddress
{
    
    
    private $formatted_address;
    private $lat;
    private $lng;
    private $place_id;
    private $street_number;
    private $route;
    private $locality;
    private $administrative_area_level_2;
    private $administrative_area_level_1;
    private $country;
    private $postal_code;
    private $postal_code_suffix;

    public function setFormattedAddress($formatted_address){
        $this->formatted_address = $formatted_address;
        return $this;
    }
    public function setLat($lat){
        $this->lat = $lat;
        return $this;
    }
    public function setLng($lng){
        $this->lng = $lng;
        return $this;
    }
    public function setPlaceId($place_id){
        $this->place_id = $place_id;
        return $this;
    }
    public function setStreetNumber($street_number){
        $this->street_number = $street_number;
        return $this;
    }
    public function setRoute($route){
        $this->route = $route;
        return $this;
    }
    public function setLocality($locality){
        $this->locality = $locality;
        return $this;
    }
    public function setAdministrativeAreaLevel2($administrative_area_level_2){
        $this->administrative_area_level_2 = $administrative_area_level_2;
        return $this;
    }
    public function setAdministrativeAreaLevel1($administrative_area_level_1){
        $this->administrative_area_level_1 = $administrative_area_level_1;
        return $this;
    }
    public function setCountry($country){
        $this->country = $country;
        return $this;
    }
    public function setPostalCode($postal_code){
        $this->postal_code = $postal_code;
        return $this;
    }
    public function setPostalCodeSuffix($postal_code_suffix){
        $this->postal_code_suffix = $postal_code_suffix;
        return $this;
    }
    
    public function getFormattedAddress(){
        return $this->formatted_address;
    }
    public function getLat(){
        return $this->lat;
    }
    public function getLng(){
        return $this->lng;
    }
    public function getPlaceId(){
        return $this->place_id;
    }
    public function getStreetNumber(){
        return $this->street_number;
    }
    public function getRoute(){
        return $this->route;
        
    }
    public function getLocality(){
        return $this->locality;
    }
    public function getAdministrativeAreaLevel2(){
        return $this->administrative_area_level_2;
    }
    public function getAdministrativeAreaLevel1(){
        return $this->administrative_area_level_1;
    }
    public function getCountry(){
        return $this->country;
    }
    public function getPostalCode(){
        return $this->postal_code;
    }
    public function getPostalCodeSuffix(){
        return $this->postal_code_suffix;
    }
}
