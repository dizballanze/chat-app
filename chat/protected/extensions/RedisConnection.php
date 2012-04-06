<?php

class RedisConnection extends CApplicationComponent {

    public $hostname;

    public $port;

    /**
     * @var Redis
     */
    private $client;

    /**
     * @return Redis
     */
    public function getClient(){
        if (is_null($this->client)){
            $this->client = new Redis;
            $this->client->connect($this->hostname, $this->port);
        }
        return $this->client;
    }
}