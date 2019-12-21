<?php
namespace Slingshot;
use Slingshot\Connection;
use Slingshot\Models\Entity;
use Slingshot\Models\Relation;
use Slingshot\Set;

class Api {

    private $transformFlag = false;
    
    public function __construct($transformFlag = false) {
        // if there is a transform flag given change
        // the mode
        if($transformFlag) {
            $this->enableReturnTransform();
        }
    }

    public function enableReturnTransform() {
        $this->transformFlag = true;
    }

    public function disableReturnTransform() {
        $this->transformFlag = false;
    }

    public function getEntityTypes() {
        $path = "getEntityTypes";
        $ret  = Connection::getGuzzleClient()->request("GET",$path);
        $data = $this->parseReturn($ret);
        return $data;
    }
    
    public function getEntityByTypeAndId($type,$id,$traverse = false)  {
        // build the request string
        $path = "getEntityByTypeAndId?type=" . $type . "&id=" . $id;
        if(false !== $traverse) {
            $path .= "&traverse=" . $traverse;
        }
        $ret = Connection::getGuzzleClient()->request("GET",$path);
        $data = $this->parseReturn($ret);
        $transformed = $this->transformReturn($data);
        return $transformed;
    }
    
    public function getEntitiesByType($type) {
        // build the request string
        $path = "getEntitiesByType?type=" . $type;
        $ret  = Connection::getGuzzleClient()->request("GET",$path);
        $data = $this->parseReturn($ret);
        $transformed = $this->transformReturn($data);
        return $transformed;
    }

    public function getEntitiesByTypeAndValue($type,$value,$mode = false) {
        $addMode = "";
        if($mode) {
            $addMode = "&mode=" . $mode;
        }
        $path = "getEntitiesByTypeAndValue?type=" . $type . "&value=" . $value . $addMode;
        $ret  = Connection::getGuzzleClient()->request("GET",$path);
        $data = $this->parseReturn($ret);
        $transformed = $this->transformReturn($data);
        return $transformed;
    }

    public function getEntitiesByValue($value,$mode = false) {
        $addMode = "";
        if($mode) {
            $addMode = "&mode=" . $mode;
        }
        $path = "getEntitiesByValue?value=" . $value . $addMode;
        $ret  = Connection::getGuzzleClient()->request("GET",$path);
        $data = $this->parseReturn($ret);
        $transformed = $this->transformReturn($data);
        return $transformed;
    }

    public function getParentEntities($type,$id) {
        $path = "getParentEntities?type=" . $type . "&id=" . $id;
        $ret  = Connection::getGuzzleClient()->request("GET",$path);
        $data = $this->parseReturn($ret);
        $transformed = $this->transformReturn($data);
        return $transformed;
    }

    public function getChildEntities($type,$id) {
        $path = "getChildEntities?type=" . $type . "&id=" . $id;
        $ret  = Connection::getGuzzleClient()->request("GET",$path);
        $data = $this->parseReturn($ret);
        $transformed = $this->transformReturn($data);
        return $transformed;
    }
    
    public function createEntity($type,$value,$properties = [],$context = "") {
        if([] == $properties) {
            $properties = new \stdClass();
        }
        $ret = Connection::getGuzzleClient()->request("POST", "createEntity", [
            \GuzzleHttp\RequestOptions::JSON => [
                'Type' => $type,
                "Value" => $value,
                "Properties" => $properties,
                "Context" => $context
            ]
        ]);
        $data = $this->parseReturn($ret);
        return $data;
    }
    
    public function updateEntity($type,$id,$value,$properties = [],$context = "",$version) {
        if([] == $properties) {
            $properties = new \stdClass();
        }
        $ret = Connection::getGuzzleClient()->request("PUT", "updateEntity", [
            \GuzzleHttp\RequestOptions::JSON => [
                'Type' => $type,
                'ID' => $id,
                "Value" => $value,
                "Properties" => $properties,
                "Context" => $context,
                "Version" => $version
            ]
        ]);
        $data = $this->parseReturn($ret);
        return $data;
    }
    
    public function deleteEntity($type,$id) {
        $path = "deleteEntity?id=" . $id . "&type=" . $type; 
        $ret = Connection::getGuzzleClient()->request("DELETE", $path);
        $this->parseReturn($ret);
    }

    public function mapJson($json) {
        $ret = $this->client->request("POST", "mapJson", [
           "body" => $json
        ]);
        $data = $this->parseReturn($ret);
        return $data;
    }

    public function getRelation($srcType,$srcID,$targetType,$targetID) {
        $path = "getRelation?srcType=" . $srcType . "&srcID=" . $srcID . "&targetType=" . $targetType . "&targetID=" . $targetID;
        $ret  = Connection::getGuzzleClient()->request("GET",$path);
        $data = $this->parseReturn($ret);
        $transformed = $this->transformReturn($data);
        return $transformed;
    }
    
    public function createRelation($srcType,$srcID,$targetType,$targetID,$properties = [],$context = "") {
        if([] == $properties) {
            $properties = new \stdClass();
        }
        $ret = Connection::getGuzzleClient()->request("POST", "createRelation", [
            \GuzzleHttp\RequestOptions::JSON => [
                'SourceType' => $srcType,
                "SourceID" => $srcID,
                'TargetType' => $targetType,
                "TargetID" => $targetID,
                "Properties" => $properties,
                "Context" => $context
            ]
        ]);
        $data = $this->parseReturn($ret);
        return $data;
    }
    
    public function updateRelation($srcType,$srcID,$targetType,$targetID,$properties = [],$context = "",$version) {
        if([] == $properties) {
            $properties = new \stdClass();
        }
        $ret = Connection::getGuzzleClient()->request("PUT", "updateRelation", [
            \GuzzleHttp\RequestOptions::JSON => [
                'SourceType' => $srcType,
                "SourceID" => $srcID,
                'TargetType' => $targetType,
                "TargetID" => $targetID,
                "Properties" => $properties,
                "Context" => $context,
                "Version" => $version
            ]
        ]);
        $data = $this->parseReturn($ret);
        return $data;
    }
    
    public function deleteRelation($srcType,$srcID,$targetType,$targetID){
        $path = "deleteRelation?srcType=" . $srcType . "&srcID=" . $srcID . "&targetType=" . $targetType ."&targetID=" . $targetID; 
        $ret = Connection::getGuzzleClient()->request("DELETE", $path);
        $this->parseReturn($ret);
    }
    
    public function parseReturn($ret) {
        // retrieve the message body
        $data = $ret->getBody();

        // check if the return code is fine, else
        // throw an exception
        if($ret->getStatusCode() != 200) {
            throw new \Exception($data);
        }

        // seems fine lets decode that stuff
        return json_decode($data,true);
    }

    private function transformReturn($data) {
        // if we dont have a transform flag,
        // we just return the data
        if(false == $this->transformFlag) {
            return $data;
        } else {
            // preset in case we only get relations but no entities
            $set = new Set();
        }

        // if there are any entities
        if(0 < count($data["Entities"])) {
            $set = $this->recursiveTransformChildren($data["Entities"]);
        }

        // if there are relations
        if(0 < count($data["Relations"])) {
            foreach($data["Relations"] as $relation) {
                // prepare the new relation
                $newRelation = new Relation();
                $newRelation->inject(
                    $relation["SourceType"],
                    $relation["SourceID"],
                    $relation["TargetType"],
                    $relation["TargetID"],
                    $relation["Properties"],
                    $relation["Context"],
                    $relation["Version"]
                );
                
                // add it to the set
                $set->addRelation(
                    $relation["SourceType"],
                    $relation["SourceID"],
                    $relation["TargetType"],
                    $relation["TargetID"],
                    $newRelation
                );
            }
        }

        return $set;
    }

    private function recursiveTransformChildren($data) {
        // prepare a set
        $set = new Set();

        // first we go through the entities
        foreach ($data as $entity) {
            // transform´the entity API data to entity instance
            $newEntity = new Entity();
            $newEntity->inject(
                $entity["Type"],
                $entity["ID"],
                $entity["Value"],
                $entity["Properties"],
                $entity["Context"],
                $entity["Version"]
            );
            
            // if there are children
            if(isset($entity["Children"]) && 0 < count($entity["Children"])) {
                // walk recursive through them and add them as set
                $newEntity->setChildren($this->recursiveTransformChildren($entity["Children"]));
            }

            // finally we add the entity to the set
            $set->addEntity(
                $entity["Type"],
                $entity["ID"],
                $newEntity
            );
        }

        return $set;
    }

}
