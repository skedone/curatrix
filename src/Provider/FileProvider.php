<?php

namespace Curatrix\Provider;


use Symfony\Component\Yaml\Yaml;

class FileProvider extends AbstractProvider implements ProviderInterface {

    private $configuration = [];

    public function __construct(array $configuration = array())
    {
        if(empty($configuration['file'])) {
            throw new \Exception('For FileProvider you must use file.');
        }

        try {
            $this->configuration = Yaml::parse(@file_get_contents(__DIR__ . '/../../' . $configuration['file']));
        } catch(\Exception $e) {
            throw new \Exception($e->getMessage());
        }

    }

    public function getCommands()
    {
        return $this->configuration['processes'];
    }
}