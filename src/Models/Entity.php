<?php
namespace Slingshot\Models;
use Slingshot\Api;
use Slingshot\Set;

class Entity  {
    
    private $id = null;
    private $type = null;
    private $properties = null;
    private $context = null;
    private $value = null;
    private $version = null;
    private $exists = false;
    private $children;
    private $api;
    
    public function __construct($type = false, $id = false) {
        $this->api = new Api();
        if(false !== $id && false !== $type) {
            $this->__load($type, $id);
        }
    }
    
    public function setID($id) {
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
    
    public function getID() {
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
        if(null == $this->properties) {
            $this->properties = [];
        }
        $this->properties[$key] = $value;
    }
    
    public function getProperties() {
        return $this->properties;
    }
    
    public function getProperty($key) {
        if(is_array($this->properties) && isset($this->properties[$key])) {
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

    public function getVersion() {
        return $this->version;
    }

    public function setVersion($version) {
        $this->version = $version;
    }

    private function toogleExistence() {
        if(true == $this->exists) {
            $this->exists = false;
        } else {
            $this->exists = true;
        }
    }
    
    private function __load($type,$id) {
        $ret = $this->api->getEntityByTypeAndId($type,$id);
        $this->setType($type);
        $this->setID($id);
        $this->exists = true;
        $entity = $ret["Entities"][0];
        $this->setValue($entity["Value"]);
        $this->setProperties($entity["Properties"]);
        $this->setContext($entity["Context"]);
        $this->setVersion($entity["Version"]);
    }
    
    public function save($create = true) {
        if(!$this->exists && $create) {
            $this->__create();
        } else if($this->exists) {
            $this->__update();
        }
    }
    
    public function delete() {
        if($this->exists) {
            $this->__delete();
        }
    }
    
    private function __update() {
        $this->api->updateEntity(
            $this->getType(),
            $this->getID(),
            $this->getValue(),
            $this->getproperties(),
            $this->getContext(),
            $this->getVersion()
        );
        $this->__load($this->getType(),$this->getID());
    }

    public function setChildren($children) {
        $this->children = $children;
    }
    
    private function __create() {
        $data = $this->api->createEntity(
            $this->getType(),
            $this->getValue(),
            $this->getproperties(),
            $this->getContext()
        );
        $this->setID($data["Entities"][0]["ID"]);
        $this->exists = 1;
    }

    public function inject($type,$id,$value,$properties,$context,$version) {
        $this->setType($type);
        $this->setID($id);
        $this->setValue($value);
        $this->setProperties($properties);
        $this->setContext($context);
        $this->setVersion($version);
        $this->toogleExistence();
    }
    
    private function __delete() {
        $this->api->deleteEntity(
            $this->getType(),
            $this->getID()
        );
    }

}