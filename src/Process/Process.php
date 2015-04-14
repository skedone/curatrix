<?php

namespace Curatrix\Process;


class Process extends \React\ChildProcess\Process {

    /**
     * @var string
     */
    private $processKey;

    /**
     * @return string
     */
    public function getProcessKey()
    {
        return $this->processKey;
    }

    /**
     * @param string $processKey
     */
    public function setProcessKey($processKey)
    {
        $this->processKey = $processKey;
    }
}