<?php

namespace Curatrix\Storage;

class RedisStorage extends AbstractStorage implements StorageInterface {

    /**
     * @var \Predis\Client
     */
    public $client;

    public function __construct($configuration = [])
    {
        $this->client = new \Predis\Client([
            'scheme' => 'tcp',
            'host'   => '127.0.0.1',
            'port'   => 6379,
        ]);
    }

    public function ping()
    {
        $this->client->ping();
    }

    public function send($instance, array $information = [])
    {
        return $this->client->rpush('curatrix::'.$instance, \json_encode($information));
    }



}