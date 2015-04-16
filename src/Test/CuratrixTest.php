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
     */
    public function testRunWithRightConfiguration()
    {

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
