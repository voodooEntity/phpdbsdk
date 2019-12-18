<?php
namespace Slingshot;
use Slingshot\Models\Entity;
use Slingshot\Models\Relation;
use Slingshot\Set;
use Slingshot\Api;

class Set {

    const ENTITY_TYPE_NOT_EXISTING = "ERR_TYPE_NOT_EXISTING";
    const ENTITY_ID_NOT_EXISTING   = "ERR_ID_NOT_EXISTING";

    private $entities = [];
    private $relations = [];
    

    public function addEntity($type,$id,$entity) {
        // make sure sub array of type exists
        if(!isset($this->entities[$type])) {
            $this->entities[$type] = [];
        }

        // add the dataset
        $this->entities[$type][$id] = $entity;
    }

    public function addRelation($sourceType,$sourceID,$targetType,$targetID,$relation) {
        // make sure source type array exists
        if(!isset($this->relations[$sourceType])) {
            $this->relations[$sourceType] = [];
        }

        // and source id array
        if(!isset($this->relations[$sourceType][$sourceID])) {
            $this->relations[$sourceType][$sourceID] = [];
        }

        // target type array
        if(!isset($this->relations[$sourceType][$sourceID][$targetType])) {
            $this->relations[$sourceType][$sourceID][$targetType] = [];
        }

        // everything seems fine, lets store the relation
        $this->relations[$sourceType][$sourceID][$targetType][$targetID] = $relation;
    }

    public function getRelations() {
        // prepare the ret
        $arrRet= [];

        // build the ret
        if(0 < count($this->relations)) {
            foreach($this->relations as $relation) {
                $arrRet[] = $relation;
            }
        }

        // return the relations
        return $arrRet;
    }



    public function getEntity($type,$id) {
        // first check if the type exists
        if(!isset($this->entities[$type])) {
            throw new \Exception(ENTITY_TYPE_NOT_EXISTING);
            return false;
        }

        // now we check if the id exists, if yes we return, if no err0r
        if(!isset($this->entities[$type][$id])) {
            throw new \Exception(ENTITY_ID_NOT_EXISTING);
            return false;
        }

        // seems fine lets return
        return $this->entities[$type][$id];
    }

    public function getEntities() {
        // prepare the ret
        $arrRet= [];

        // build the ret
        if(0 < count($this->entities)) {
            foreach($this->entities as $sub) {
                foreach($sub as $entity) {
                    $arrRet[] = $entity;
                }
            }
        }

        // return the entities
        return $arrRet;
    }


}


?>