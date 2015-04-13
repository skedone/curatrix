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
    public function __construct()
    {
        $this->client = new \Predis\Client([
            'scheme' => 'tcp',
            'host'   => '127.0.0.1',
            'port'   => 6379,
        ]);
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getCommands()
    {
        $configuration = $this->client->get('curatrix::configuration');
        if(empty($configuration)) {
            throw new \Exception("No configuration found inside RedisProvider.");
        }
        return \json_decode($configuration, TRUE);
    }
}