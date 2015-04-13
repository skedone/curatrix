<?php

namespace Curatrix\Storage;


interface StorageInterface {

    /**
     * @param string $instance
     * @param array $information
     * @return boolean
     */
    public function send($instance, array $information = []);

    /**
     * @return boolean
     */
    public function ping();
}