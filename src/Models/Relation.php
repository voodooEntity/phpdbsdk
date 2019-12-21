<?php
namespace Slingshot\Models;
use Slingshot\Api;
use Slingshot\Set;

class Relation  {
    
    private $sourceID = null;
    private $sourceType = null;
    private $targetID = null;
    private $targetType = null;
    private $properties = null;
    private $context = null;
    private $version = null;
    private $exists = false;
    private $api;
    
    public function __construct($sourceType = false,$sourceID = false,$targetType = false,$targetID = false) {
        $this->api = new Api();
        if(false !== $sourceType && false !== $sourceID && false !== $targetType && false !== $targetID ) {
            $this->__load($sourceType,$sourceID,$targetType,$targetID);
        }
    }
    
    public function setSourceType($type) {
        if($this->exists) {
            return false;
        }
        $this->sourceType = $type;
    }
    
    public function getSourceType() {
        return $this->sourceType;
    }
    
    public function setTargetType($type) {
        if($this->exists) {
            return false;
        }
        $this->targetType = $type;
    }
    
    public function getTargetType() {
        return $this->targetType;
    }
    
    public function setSourceID($type) {
        if($this->exists) {
            return false;
        }
        $this->sourceID = $type;
    }
    
    public function getSourceID() {
        return $this->sourceID;
    }
    
    public function setTargetID($type) {
        if($this->exists) {
            return false;
        }
        $this->targetID = $type;
    }
    
    public function getTargetID() {
        return $this->targetID;
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

    public function getVersion() {
        return $this->version;
    }

    public function setVersion($version) {
        $this->version = $version;
    }

    private function __load($sourceType,$sourceID,$targetType,$targetID) {
        $ret = $this->api->getRelation($sourceType,$sourceID,$targetType,$targetID);
        $this->setSourceType($sourceType);
        $this->setSourceID($sourceID);
        $this->setTargetType($targetType);
        $this->setTargetID($targetID);
        $this->exists = true;
        $relation = $ret["Relations"][0];
        $this->setProperties($relation["Properties"]);
        $this->setContext($relation["Context"]);
        $this->setVersion($relation["Version"]);
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
        $this->api->updateRelation(
            $this->getSourceType(),
            $this->getSourceID(),
            $this->getTargetType(),
            $this->getTargetID(),
            $this->getProperties(),
            $this->getContext(),
            $this->getVersion()
        );
    }
    
    private function __create() {
        $data = $this->api->createRelation(
            $this->getSourceType(),
            $this->getSourceID(),
            $this->getTargetType(),
            $this->getTargetID(),
            $this->getProperties(),
            $this->getContext()
        );
        $this->exists = 1;
    }
    
    private function __delete() {
        $this->api->deleteRelation(
            $this->getSourceType(),
            $this->getSourceID(),
            $this->getTargetType(),
            $this->getTargetID()
        );
    }

    public function inject($srcType,$srcID,$targetType,$targetID,$properties,$context,$version) {
        $this->setSourceType($srcType);
        $this->setSourceID($srcID);
        $this->setTargetType($targetType);
        $this->setTargetID($targetID);
        $this->setProperties($properties);
        $this->setContext($context);
        $this->setVersion($version);
        $this->toogleExistence();
    }

    private function toogleExistence() {
        if(true == $this->exists) {
            $this->exists = false;
        } else {
            $this->exists = true;
        }
    }
    
}