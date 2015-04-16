<?php

namespace Curatrix\Test;


use Curatrix\SupervisorCommand;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class SupervisorCommandTest
 * @package Curatrix\Test
 * @covers \Curatrix\SupervisorCommand
 */
class SupervisorCommandTest extends \PHPUnit_Framework_TestCase {

    /**
     * @covers \Curatrix\SupervisorCommand::execute
     * @expectedException \Exception
     */
    public function testFailWithoutConfigurationFile()
    {
        $application = new Application();
        $application->add(new SupervisorCommand());

        $command = $application->find('run');
        $test = new CommandTester($command);
        $test->execute(['command' => $command->getName()]);

    }
}
