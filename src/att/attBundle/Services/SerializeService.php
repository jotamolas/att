<?php

namespace att\attBundle\Services;


use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;



class SerializeService {
    
    private $encoders = array();
    private $normalizers = array();
    private $serializer;
    
    
    public function __construct() {
        
        $this->encoders[] = new XmlEncoder();
        $this->encoders[] = new JsonEncoder();
        $this->normalizers[] = new ObjectNormalizer();
        $this->serializer = new Serializer($this->normalizers, $this->encoders);

    }
    
    
    public function serializeJsonObject($object){
        return  $this->serializer->serialize($object, 'json');
    }
    
    public function serializeJsonArray($objects = array()){
        $arrayOfSerializableObjects = array();
        
        foreach ($objects as $object) {
            $arrayOfSerializableObjects [] = $this->serializeJsonObject($object);
        }
        
        return $arrayOfSerializableObjects;
    }
    
    
    public function deserializeJsonObject($data, $class, $object){
        
      return $this->serializer->deserialize($data, $class, 'json', ['object_to_populate ' => $object]);  
    }
    
 
}


    