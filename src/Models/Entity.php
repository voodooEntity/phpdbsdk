<?php
namespace MERDE\Models;
use MERDE\Api;

class Entity  {
    
    private $id;
    private $type;
    private $properties = [];
    private $context;
    private $value;
    private $exists = false;
    
    public function __construct($type = false, $id = false) {
        if(false !== $id && false !== $type) {
            $this->__load($type, $id);
        }
    }
    
    public function getId() {
        return $this->id;
    }
    
    public function getType() {
        return $this->type;
    }
    
    public function setContext($context) {
        $this->context = $context;
    }
    
    public function getContext() {
        return $this->context;
    }
    
    public function setProperties($properties) {
        if(is_array($properties)) {
            $this->properties = $properties;
        }
    }
    
    public function addProperty($key,$value) {
        $this->properties[$key] = $value;
    }
    
    public function getProperties() {
        return $this->properties;
    }
    
    public function getProperty($key) {
        if(isset($this->properties[$key])) {
            return $this->properties[$key];
        }
        return null;
    }
    
    public function setValue($value) {
        $this->value = $value;
    }
    
    private function __load($type,$id) {
        $api = new Api();
        $ret = $api->getEntityByTypeAndId($type,$id);
        $this->type = $type;
        $this->id = $id;
        $entity = $ret["Entities"][0];
        $this->setValue($entity["Value"]);
        $this->setProperties($entity["Properties"]);
        $this->setContext($entity["Context"]);
    }
    
}