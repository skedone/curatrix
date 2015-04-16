<?php

namespace Curatrix;

use Curatrix\Process\Process;
use Curatrix\Process\ProcessCollection;
use Curatrix\Provider\ProviderInterface;
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
     * @var boolean
     */
    private $debug;

    /**
     * @const float
     */
    const TIMER_PROCESSES_RESPAWN = 1;

    /**
     * @const float
     */
    const TIMER_TRACK_SYESTEM = 1;

    /**
     * @param ProviderInterface $providerInterface
     * @param StorageInterface $storageInterface
     * @param array $options
     */
    public function __construct(ProviderInterface $providerInterface, StorageInterface $storageInterface, array $options = [])
    {
        $this->provider = $providerInterface;
        $this->storage = $storageInterface;

        $this->debug = (empty($options['debug']) || $options === false ? false : true);

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

        $cmds = $this->provider->getCommands();

        $spawnableProcess = $this->processes->resolve($cmds);

        foreach ($spawnableProcess as $k => $d) {
            $command = $cmds[$k];
            for ($i = 0; $i < $command['workers']; $i++) {
                $process = new Process($command['cmd']);
                $process->setProcessKey($command['key']);
                $uuid = \uniqid();

                $this->loop->addPeriodicTimer(self::TIMER_TRACK_SYESTEM, function (Timer $timer) use( $uuid, $process ) {
                    if($process->isRunning()) {
                        return $this->storage->worker($uuid, Profiling::getInformation());
                    }

                    $timer->cancel();
                    return true;
                });

                $process->on('exit', function($timer) use ($uuid) {
                    $this->storage->delete($uuid);
                });

                $process->start($this->loop);

                $this->processes->add($process);
            }
        }
    }
}