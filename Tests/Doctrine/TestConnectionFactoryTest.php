<?php declare(strict_types=1);

namespace RichCongress\WebTestBundle\Tests\Doctrine;

use Doctrine\Bundle\DoctrineBundle\ConnectionFactory;
use Doctrine\DBAL\Connection;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery\MockInterface;
use RichCongress\WebTestBundle\Doctrine\TestConnectionFactory;

/**
 * Class TestConnectionFactoryTest
 *
 * @package    RichCongress\WebTestBundle\Tests\Doctrine
 * @author     Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright  2014 - 2020 RichCongress (https://www.richcongress.com)
 *
 * @covers     \RichCongress\WebTestBundle\Doctrine\TestConnectionFactory
 */
final class TestConnectionFactoryTest extends MockeryTestCase
{
    /** @var ConnectionFactory|MockInterface */
    private $innerFactory;

    /** @var TestConnectionFactory */
    private $factory;

    public function setUp(): void
    {
        $this->innerFactory = \Mockery::mock(ConnectionFactory::class);
        $this->factory = new TestConnectionFactory($this->innerFactory);
    }

    public function tearDown(): void
    {
        putenv('TEST_TOKEN=' . ($_ENV['TEST_TOKEN'] ?? ''));
    }

    public function testWithTestToken(): void
    {
        putenv('TEST_TOKEN=test');
        $connection = \Mockery::mock(Connection::class);

        $expectedParams = [
            'dbname'           => 'dbTest_test',
            'dama.keep_static' => true,
            'path'             => '/',
        ];

        $this->innerFactory
            ->shouldReceive('createConnection')
            ->with($expectedParams, null, null, [])
            ->andReturn($connection);

        $this->factory->createConnection([
            'path' => '/'
        ]);
    }

    public function testWithDbName(): void
    {
        $connection = \Mockery::mock(Connection::class);

        $expectedParams = [
            'dbname'           => 'dbName',
            'dama.keep_static' => true,
            'path'             => '/',
        ];

        $this->innerFactory
            ->shouldReceive('createConnection')
            ->with($expectedParams, null, null, [])
            ->andReturn($connection);

        $this->factory->createConnection([
            'path'   => '/',
            'dbname' => 'dbName',
        ]);

        \Mockery::close();
    }

    public function testWithDbNameInPath(): void
    {
        $connection = \Mockery::mock(Connection::class);

        $expectedParams = [
            'dbname'           => 'dbTest',
            'dama.keep_static' => true,
            'path'             => '/dbTest',
        ];

        $this->innerFactory
            ->shouldReceive('createConnection')
            ->with($expectedParams, null, null, [])
            ->andReturn($connection);

        $this->factory->createConnection([
            'path'   => '/__DBNAME__',
        ]);

        \Mockery::close();
    }
}
