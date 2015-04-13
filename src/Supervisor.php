<?php

namespace Curatrix;

use Curatrix\Process\Process;
use Curatrix\Process\ProcessCollection;
use Curatrix\Provider\RedisProvider;
use Curatrix\Storage\StorageInterface;
use Curatrix\System\Profiling;
use React\EventLoop\Timer\Timer;

/**
 * Class Supervisor
 * @package Curatrix
 */
class Supervisor
{

    /**
     * @var ProcessCollection
     */
    private $processes;

    /**
     * @var StorageInterface
     */
    private $storage;

    /**
     * @var \React\EventLoop\ExtEventLoop|\React\EventLoop\LibEventLoop|\React\EventLoop\LibEvLoop|\React\EventLoop\StreamSelectLoop
     */
    private $loop;

    /**
     * @var array
     */
    private $commands;

    /**
     * @const float
     */
    const TIMER_PROCESSES_RESPAWN = 0.5;

    /**
     * @const float
     */
    const TIMER_TRACK_SYESTEM = 5;

    /**
     * @param $configuration array
     */
    public function __construct($configuration, StorageInterface $storage)
    {
        $this->provider = new RedisProvider();
        $this->storage = $storage;

        $this->processes = new ProcessCollection();
        $this->loop = \React\EventLoop\Factory::create();
    }

    /**
     * Loop
     */
    public function handle()
    {

        /**
         * Send information about the server itself
         */
        $this->loop->addPeriodicTimer(self::TIMER_PROCESSES_RESPAWN, function (Timer $timer) {
            $this->spawn($timer);
        });

        /**
         * Send information about the server itself
         */
        $this->loop->addPeriodicTimer(self::TIMER_TRACK_SYESTEM, function (Timer $timer) {
            $this->storage->send(Profiling::getInstance(), Profiling::getInformation());
        });

        $this->loop->run();
    }

    /**
     * Clear process, check configuration and decide if spawn or not jobs
     */
    private function spawn(Timer $timer)
    {
        $this->processes->clear();

        $do = $this->processes->resolve($this->provider->getCommands());
        foreach ($do as $k => $d) {
            $command = $this->commands[$k];
            for ($i = 0; $i < $command['workers']; $i++) {
                $process = new Process($command['cmd']);
                $process->setProcessKey($command['key']);

                $this->loop->addPeriodicTimer(self::TIMER_TRACK_SYESTEM, function (Timer $timer) use ($command, $process) {
                    $this->storage->send($command['key'] . '-' . $process->getPid(), Profiling::getPidInformation($process->getPid()));
                });
                $process->start($this->loop);
                $this->processes->add($process);
            }
        }
    }
}