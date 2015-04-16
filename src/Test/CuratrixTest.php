<?php

namespace Curatrix\Test;
use Curatrix\Curatrix;

/**
 * Class CuratrixTest
 * @package Curatrix\Test
 */
class CuratrixTest extends \PHPUnit_Framework_TestCase {

    public function testTrueIsTrue()
    {
        $foo = true;
        $this->assertTrue($foo);
    }

    /**
     * @covers \Curatrix\Curatrix::run
     * @expectedException \Curatrix\Exception\ConfigurationException
     */
    public function testRunWithNoConfiguration()
    {
        Curatrix::run([]);
    }

    /**
     * @covers \Curatrix\Curatrix::run
     * @expectedException \Curatrix\Exception\ConfigurationException

    public function testRunWithRightConfiguration()
    {
        $configuration = [
            'storage' => [
                'class' => 'RedisStorage'
            ],
            'provider' => [
                'class' => 'RedisProvider'
            ]
        ];

        $run = Curatrix::run($configuration);
        $this->assertTrue($run);
    }
     * */

    /**
     * @covers \Curatrix\Curatrix::getStorage
     * @expectedException \Curatrix\Exception\ConfigurationException
     */
    public function testFactoryStorageNoConfiguration()
    {
        $configuration = array();
        $storage = Curatrix::getStorage($configuration);
    }

    /**
     * @covers \Curatrix\Curatrix::getProvider
     * @expectedException \Curatrix\Exception\ConfigurationException
     */
    public function testFactoryProviderNoConfiguration()
    {
        $configuration = array();
        $storage = Curatrix::getProvider($configuration);
    }

    /**
     * @covers \Curatrix\Curatrix::getStorage
     * @expectedException \Curatrix\Storage\Exception\StorageNotFoundException
     */
    public function testFactoryStorageWrongClass()
    {
        $configuration = array();
        $configuration['storage']['class'] = 'NotFoundStorage';
        $storage = Curatrix::getStorage($configuration);
    }

    /**
     * @covers \Curatrix\Curatrix::getProvider
     * @expectedException \Curatrix\Provider\Exception\ProviderNotFoundException
     */
    public function testFactoryProviderWrongClass()
    {
        $configuration = array();
        $configuration['provider']['class'] = 'NotFoundProvider';
        $storage = Curatrix::getProvider($configuration);
    }


    /**
     * @covers \Curatrix\Curatrix::getStorage
     * @throws \Exception
     */
    public function testFactoryStorage()
    {
        $configuration['storage']['class'] = 'RedisStorage';
        $storage = Curatrix::getStorage($configuration);
        $this->assertInstanceOf('\Curatrix\Storage\StorageInterface', $storage);

    }

    /**
     * @covers \Curatrix\Curatrix::getProvider
     * @throws \Exception
     */
    public function testFactoryProvider()
    {
        $configuration['provider']['class'] = 'RedisProvider';
        $provider = Curatrix::getProvider($configuration);
        $this->assertInstanceOf('\Curatrix\Provider\ProviderInterface', $provider);
    }

}
