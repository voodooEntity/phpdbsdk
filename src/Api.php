<?php
namespace MERDE;
use MERDE\Connection;

class Api {
    
    public function __construct() {
        $this->client = Connection::getGuzzleClient();
    }

    public function getEntityTypes() {
        $path = "getEntityTypes";
        $ret  = $this->client->request("GET",$path);
        $data = $this->parseReturn($ret);
        return $data;
    }
    
    public function getEntityByTypeAndId($type,$id,$traverse = false)  {
        // build the request string
        $path = "getEntityByTypeAndId?type=" . $type . "&id=" . $id;
        if(false !== $traverse) {
            $path .= "&traverse=" . $traverse;
        }
        $ret = $this->client->request("GET",$path);
        $data = $this->parseReturn($ret);
        return $data;
    }
    
    public function getEntitiesByType($type) {
        // build the request string
        $path = "getEntitiesByType?type=" . $type;
        $ret  = $this->client->request("GET",$path);
        $data = $this->parseReturn($ret);
        return $data;
    }

    public function getEntitiesByTypeAndValue($type,$value) {
        $path = "getEntitiesByTypeAndValue?type=" . $type . "&value=" . $value;
        $ret  = $this->client->request("GET",$path);
        $data = $this->parseReturn($ret);
        return $data;
    }

    public function getEntitiesByValue($value) {
        $path = "getEntitiesByValue?value=" . $value;
        $ret  = $this->client->request("GET",$path);
        $data = $this->parseReturn($ret);
        return $data;
    }

    public function getParentEntities($type,$id) {
        $path = "getParentEntities?type=" . $type . "&id=" . $id;
        $ret  = $this->client->request("GET",$path);
        $data = $this->parseReturn($ret);
        return $data;
    }

    public function getChildEntities($type,$id) {
        $path = "getChildEntities?type=" . $type . "&id=" . $id;
        $ret  = $this->client->request("GET",$path);
        $data = $this->parseReturn($ret);
        return $data;
    }
    
    public function parseReturn($ret) {
        $data = $ret->getBody();
        if($ret->getStatusCode() != 200) {
            throw new \Exception($data);
        }
        return json_decode($data,true);
    }
    
    public function createEntity($type,$value,$properties = [],$context = "") {
        if([] == $properties) {
            $properties = new stdClass();
        }
        $ret = $this->client->request("POST", "createEntity", [
            \GuzzleHttp\RequestOptions::JSON => [
                'Type' => $type,
                "Value" => $value,
                "Properties" => $properties,
                "Context" => $context
            ]
        ]);
        $data = $this->parseReturn($ret);
    }
    
    public function updateEntity($type,$id,$value,$properties = [],$context = "") {
        if([] == $properties) {
            $properties = new stdClass();
        }
        $ret = $this->client->request("PUT", "updateEntity", [
            \GuzzleHttp\RequestOptions::JSON => [
                'Type' => $type,
                'ID' => $id,
                "Value" => $value,
                "Properties" => $properties,
                "Context" => $context
            ]
        ]);
        $data = $this->parseReturn($ret);
    }
    
    public function deleteEntity($type,$id) {
        $path = "deleteEntity?id=" . $id . "&type=" . $type; 
        $ret = $this->client->request("DELETE", $path);
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
        $ret  = $this->client->request("GET",$path);
        $data = $this->parseReturn($ret);
        return $data;
    }
    
    public function createRelation($srcType,$srcID,$targetType,$targetID,$properties = [],$context = "") {
        if([] == $properties) {
            $properties = new stdClass();
        }
        $ret = $this->client->request("POST", "createRelation", [
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
    }
    
    public function updateRelation($srcType,$srcID,$targetType,$targetID,$properties = [],$context = "") {
        if([] == $properties) {
            $properties = new stdClass();
        }
        $ret = $this->client->request("PUT", "updateRelation", [
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
    }
    
    public function deleteRelation($srcType,$srcID,$targetType,$targetID){
        $path = "deleteRelation?srcType=" . $srcType . "&srcID=" . $srcID . "&targetType=" . $targetType ."&targetID=" . $targetID; 
        $ret = $this->client->request("DELETE", $path);
        $this->parseReturn($ret);
    }
    
}
