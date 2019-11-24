<?php
namespace MERDE\Models;
use MERDE\Api;

class Entity  {
    
    private $id = null;
    private $type = null;
    private $properties = [];
    private $context = null;
    private $value = null;
    private $exists = false;
    private $api;
    
    public function __construct($type = false, $id = false) {
        if(false !== $id && false !== $type) {
            $this->__load($type, $id);
        }
        $this->api = new Api();
    }
    
    public function setId($id) {
        if($this->exists) {
            return false;
        }
        $this->id = $id;
    }
    
    public function setType($type) {
        if($this->exists) {
            return false;
        }
        $this->type = $type;
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
    
    public function getValue() {
        return $this->value;
    }
    
    private function __load($type,$id) {
        $ret = $this->api->getEntityByTypeAndId($type,$id);
        $this->setType($type);
        $this->setId($id);
        $this->exists = true;
        $entity = $ret["Entities"][0];
        $this->setValue($entity["Value"]);
        $this->setProperties($entity["Properties"]);
        $this->setContext($entity["Context"]);
    }
    
    public function save($create = true) {
        if(!$this->exists && $create) {
            $this->__create();
        } else if($this->exists) {
            $this->__update();
        }
    }
    
    private function __update() {
        $this->api->updateEntity(
            $this->getType(),
            $this->getId(),
            $this->getValue(),
            $this->getproperties(),
            $this->getContext()
        );
    }
    
    private function __create() {
        $data = $this->api->createEntity(
            $this->getType(),
            $this->getValue(),
            $this->getproperties(),
            $this->getContext()
        );
        $this->setId($data["Entities"][0]["ID"]);
        $this->exists = 1;
    }
    
}