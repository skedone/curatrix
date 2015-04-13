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

        $commands = $this->provider->getCommands();
        print_r($commands);
        $do = $this->processes->resolve($commands);

        if($this->debug) {
            if(count($do) < 1) {
                print "\n ## Nothing to spawn ##\n";
            } else {
                print "\n ## To be spawned ##\n";
                foreach($do as $w => $c) {
                    print "\n" . $commands[$w]['cmd'] . " - " . $commands[$w]['key'] . " - " . $commands[$w]['workers'];
                }
            }
        }
        foreach ($do as $k => $d) {
            $command = $commands[$k];
            for ($i = 0; $i < $command['workers']; $i++) {
                $process = new Process($command['cmd']);
                $process->setProcessKey($command['key']);

                $process->start($this->loop);

                $this->processes->add($process);
            }
        }
    }
}