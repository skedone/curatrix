<?php

namespace Curatrix\Provider;


class RedisProvider extends AbstractProvider implements ProviderInterface {

    /**
     * @var \Predis\Client
     */
    private $client;

    /**
     *
     */
    public function __construct(array $configuration = [])
    {
        $this->client = new \Predis\Client([
            'scheme' => $configuration['scheme'] ?: 'tcp',
            'host'   => $configuration['host'] ?: '127.0.0.1',
            'port'   => $configuration['port'] ?: 6379,
        ]);
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getCommands()
    {
        $configuration = \json_decode($this->client->get('curatrix::configuration'), TRUE);
        return $configuration['processes'];
    }
}