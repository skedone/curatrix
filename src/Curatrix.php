<?php

namespace Curatrix;

use Curatrix\Exception\ConfigurationException;
use Curatrix\Provider\Exception\ProviderNotFoundException;
use Curatrix\Storage\Exception\StorageNotFoundException;

class Curatrix {

    public static function run(array $configuration = array())
    {
        if(empty($configuration)) {
            throw new ConfigurationException('You must provide a configuration file for Curatrix');
        }

        $storage = self::getStorage($configuration);
        $provider = self::getProvider($configuration);

        $supervisor = new Supervisor($provider, $storage, $configuration);
        $supervisor->handle();

        return TRUE;

    }

    public static function getStorage(array $configuration = array())
    {
        if(empty($configuration['storage']['class'])) {
            $message = "You must provide a class for the storage.";
            throw new ConfigurationException($message);
        }
        $namespace = "Curatrix\\Storage";
        $storageNameSpace = join('\\', [$namespace, $configuration['storage']['class']]);
        if (!class_exists($storageNameSpace)) {
            $message = "The storage for $storageNameSpace is not available.";
            throw new StorageNotFoundException($message);
        }

        $parameters = empty($configuration['storage']['parameters']) ? [] : $configuration['storage']['parameters'];
        return new $storageNameSpace($parameters);
    }

    public static function getProvider(array $configuration = array())
    {
        if(empty($configuration['provider']['class'])) {
            $message = "You must provide a class for the provider.";
            throw new ConfigurationException($message);
        }
        $namespace = "Curatrix\\Provider";
        $providerNameSpace = join('\\', [$namespace, $configuration['provider']['class']]);
        if (!class_exists($providerNameSpace)) {
            $message = "The provider for $providerNameSpace is not available.";
            throw new ProviderNotFoundException($message);
        }

        $parameters = empty($configuration['provider']['parameters']) ? [] : $configuration['provider']['parameters'];
        return new $providerNameSpace($parameters);
    }
}