<?php

namespace Curatrix;

class Curatrix {

    public static function run(array $configuration = array())
    {
        if(empty($configuration)) {
            throw new \Exception('You must provide a configuration file for Curatrix');
        }

        $storage = self::getStorage($configuration);
        $provider = self::getProvider($configuration);

        $supervisor = new Supervisor($provider, $storage, $configuration);
        $supervisor->handle();

        return TRUE;

    }

    public static function getStorage(array $configuration = array())
    {
        $namespace = "Curatrix\\Storage";
        $storageNameSpace = join('\\', [$namespace, $configuration['storage']['class']]);
        if (!class_exists($storageNameSpace)) {
            $message = "The storage for $storageNameSpace is not available.";
            throw new \Exception($message);
        }

        $parameters = empty($configuration['storage']['parameters']) ? [] : $configuration['storage']['parameters'];
        return new $storageNameSpace($parameters);
    }

    public static function getProvider(array $configuration = array())
    {
        $namespace = "Curatrix\\Provider";
        $providerNameSpace = join('\\', [$namespace, $configuration['provider']['class']]);
        if (!class_exists($providerNameSpace)) {
            $message = "The provider for $providerNameSpace is not available.";
            throw new \Exception($message);
        }

        $parameters = empty($configuration['provider']['parameters']) ? [] : $configuration['provider']['parameters'];
        return new $providerNameSpace($parameters);
    }
}