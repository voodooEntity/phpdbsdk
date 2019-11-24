<?php
namespace MERDE;
use MERDE\Connection;

class Api {
    
    public function __construct() {
        $this->client = Connection::getGuzzleClient();
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
        $ret = $this->client->request("GET",$path);
        $data = $this->parseReturn($ret);
        return $data;
    }
    
    public function parseReturn($ret) {
        $data = $ret->getBody();
        $transport = json_decode($data,true);
        if($transport["State"] == "error") {
            throw new \Exception($transport["Error"]);
        }
        return $transport["Data"];
    }
    
}


?>