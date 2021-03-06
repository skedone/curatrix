<?php

namespace Curatrix\Storage;

class RedisStorage extends AbstractStorage implements StorageInterface {

    /**
     * @var \Predis\Client
     */
    public $client;

    /**
     * @param array $configuration
     */
    public function __construct(array $configuration = [])
    {
        $this->client = new \Predis\Client([
            'scheme' => (empty($configuration['scheme']) ? 'tcp' : $configuration['scheme']),
            'host'   => (empty($configuration['host']) ? '127.0.0.1' : $configuration['host']),
            'port'   => (empty($configuration['port']) ? 6379 : $configuration['port']),
        ]);
    }

    /**
     *
     */
    public function ping()
    {
        $this->client->ping();
    }

    /**
     * @param string $instance
     * @param array $information
     * @return int
     */
    public function send($instance, array $information = [])
    {
        return $this->client->set('curatrix::instance::' . $instance, \json_encode($information));
    }

    public function worker($worker, array $information = [])
    {
        return $this->client->set('curatrix::workers::' . $worker, \json_encode($information));
    }

    public function delete($worker)
    {
        return $this->client->del(['curatrix::workers::' . $worker]);
    }


}