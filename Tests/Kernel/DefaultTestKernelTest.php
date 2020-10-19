<?php declare(strict_types=1);

namespace RichCongress\WebTestBundle\Tests\Kernel;

use DAMA\DoctrineTestBundle\DAMADoctrineTestBundle;
use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use RichCongress\WebTestBundle\Kernel\DefaultTestKernel;
use RichCongress\WebTestBundle\RichCongressWebTestBundle;
use RichCongress\WebTestBundle\TestCase\TestCase;
use RichCongress\WebTestBundle\Tests\Resources\config\TestKernel;
use RichCongress\WebTestBundle\Tests\Resources\Kernel\DummyEventSubscriberKernel;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Routing\Loader\GlobFileLoader;

/**
 * Class DefaultTestKernelTest
 *
 * @package    RichCongress\WebTestBundle\Tests\Kernel
 * @author     Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright  2014 - 2020 RichCongress (https://www.richcongress.com)
 *
 * @covers \RichCongress\WebTestBundle\Kernel\DefaultTestKernel
 */
final class DefaultTestKernelTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function setUp(): void
    {
        $fileSystem = new Filesystem();
        $fileSystem->remove(__DIR__ . '/../../var');

        parent::setUp();
    }

    public function testLoadedBundles(): void
    {
        $parameterBag = $this->getService(ParameterBagInterface::class);
        $bundles = $parameterBag->get('kernel.bundles');

        self::assertContains(DoctrineBundle::class, $bundles);
        self::assertContains(FrameworkBundle::class, $bundles);
        self::assertContains(DAMADoctrineTestBundle::class, $bundles);
        self::assertContains(RichCongressWebTestBundle::class, $bundles);
        self::assertContains(SecurityBundle::class, $bundles);
    }

    public function testGetConfigurationDir(): void
    {
        $kernel = new DefaultTestKernel('inner_test', false);

        self::assertNull($kernel->getConfigurationDir());

        /** @var LoaderInterface|MockInterface $loader */
        $loader = \Mockery::mock(LoaderInterface::class);
        $loader->shouldReceive('load')->once();
        $kernel->registerContainerConfiguration($loader);
    }

    public function testRegisterContainerConfigurationWithoutConfigPath(): void
    {
        /** @var LoaderInterface|MockInterface $loader */
        $loader = \Mockery::mock(LoaderInterface::class);
        $loader->shouldReceive('load')->once();

        $kernel = new DefaultTestKernel('inner_test', false);
        $kernel->registerContainerConfiguration($loader);
    }

    public function testLoadRoutesWithoutConfigPath(): void
    {
        $loader = new GlobFileLoader(new FileLocator());
        $kernel = new DefaultTestKernel('inner_test', false);
        $routeCollection = $kernel->loadRoutes($loader);

        self::assertEmpty($routeCollection);
    }

    public function testLoadRoutes(): void
    {
        $loader = new GlobFileLoader(new FileLocator());
        $kernel = new TestKernel();
        $routeCollection = $kernel->loadRoutes($loader);

        self::assertEmpty($routeCollection);
    }
}
