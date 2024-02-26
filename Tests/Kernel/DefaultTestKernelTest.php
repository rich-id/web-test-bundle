<?php declare(strict_types=1);

namespace RichCongress\WebTestBundle\Tests\Kernel;

use DAMA\DoctrineTestBundle\DAMADoctrineTestBundle;
use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use RichCongress\TestFramework\TestConfiguration\Attribute\TestConfig;
use RichCongress\WebTestBundle\Kernel\DefaultTestKernel;
use RichCongress\WebTestBundle\RichCongressWebTestBundle;
use RichCongress\WebTestBundle\TestCase\TestCase;
use RichCongress\WebTestBundle\Tests\Resources\config\TestKernel;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use Symfony\Component\Routing\Loader\GlobFileLoader;
use Symfony\Component\Routing\RouteCollection;

/**
 * Class DefaultTestKernelTest
 *
 * @package    RichCongress\WebTestBundle\Tests\Kernel
 * @author     Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright  2014 - 2020 RichCongress (https://www.richcongress.com)
 *
 * @covers \RichCongress\WebTestBundle\Kernel\DefaultTestKernel
 */
#[TestConfig('kernel')]
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

        self::assertCount(5, $bundles);
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

    public function testRegisterBundlesWithoutConfigPath(): void
    {
        $kernel = new DefaultTestKernel('inner_test', false);
        $bundles = \iterator_to_array($kernel->registerBundles());

        self::assertCount(4, $bundles);
    }

    public function testRegisterBundlesWithConfigPath(): void
    {
        $kernel = new TestKernel();
        $bundles = \iterator_to_array($kernel->registerBundles());

        self::assertCount(5, $bundles);
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
        $kernel = new DefaultTestKernel('inner_test', false);
        $kernel->boot();
        $routeCollection = $kernel->getContainer()->get('router')->getRouteCollection();

        self::assertEmpty($routeCollection);
    }

    public function testLoadRoutes(): void
    {
        $kernel = new TestKernel();
        $kernel->boot();
        $routeCollection = $kernel->getContainer()->get('router')->getRouteCollection();

        self::assertEmpty($routeCollection);
    }
}
