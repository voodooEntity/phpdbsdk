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
        $body = $ret->getBody();
        $data = json_decode($body);
        return $data;
    }
    
    
}


?>