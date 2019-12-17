<?php
namespace Slingshot;
use \GuzzleHttp\Client;

class Connection {
    
    private static $host = false;
    private static $port = false;
    private static $version = false;
    private static $guzzleClient = false;
    
    public function __construct($host,$port,$version, $contest = false) {
        // store base info
        self::$host = $host;
        self::$port = $port;
        self::$version = $version;

        // init our http client
        self::initGuzzleClient();

        // if wanted, we also make a connection test
        if(true === $contest) {
            // test if the connection works
            self::testConnection();
        }
    }
    
    public static function initGuzzleClient() {
        $baseUrl = "http://" . self::$host . ":" . self::$port . "/" . self::$version . "/";
        self::$guzzleClient = new Client([
            'base_uri' => $baseUrl
        ]);
    }
    
    public static function testConnection() {
        $ret = self::$guzzleClient->request('GET','ping');
        if("pong" != $ret->getBody()) {
            throw new \Exception("Database server not reachable! ( " . "http://" . self::$host . ":" . self::$port . "/" . self::$version . "/ping )");
        }
    }
    
    public static function getGuzzleClient() {
        return self::$guzzleClient;
    }
    
}