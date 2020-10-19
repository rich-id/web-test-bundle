<?php

namespace RichCongress\WebTestBundle\Kernel;

use DAMA\DoctrineTestBundle\DAMADoctrineTestBundle;
use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use RichCongress\WebTestBundle\RichCongressWebTestBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\Config\Exception\LoaderLoadException;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RouteCollectionBuilder;

/**
 * Class DefaultTestKernel
 *
 * @package    RichCongress\WebTestBundle\Kernel
 * @author     Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright  2014 - 2020 RichCongress (https://www.richcongress.com)
 */
class DefaultTestKernel extends Kernel
{
    private const CONFIG_EXTS = '.{php,xml,yaml,yml}';

    /** @var string[] */
    protected static $defaultBundles = [
        DoctrineBundle::class,
        FrameworkBundle::class,
        DAMADoctrineTestBundle::class,
        RichCongressWebTestBundle::class,
    ];

    /**
     * @return array|iterable|\Traversable|BundleInterface[]
     */
    public function registerBundles(): iterable
    {
        foreach (static::$defaultBundles as $class) {
            yield new $class();
        }

        return $this->registerCustomBundles();
    }

    protected function registerCustomBundles(): iterable
    {
        $configDir = $this->getConfigurationDir();

        if ($configDir === null || !file_exists($configDir . '/bundles.php')) {
            return;
        }

        $contents = require $configDir . '/bundles.php';

        foreach ($contents as $class => $envs) {
            $appropriateEnv = $envs[$this->environment] ?? $envs['all'] ?? false;
            $isAlreadyLoaded = in_array($class, static::$defaultBundles, true);

            if ($appropriateEnv && !$isAlreadyLoaded) {
                yield new $class();
            }
        }
    }

    /**
     * @return string|null
     */
    public function getConfigurationDir(): ?string
    {
        return null;
    }

    /**
     * @param LoaderInterface $loader Resource loader.
     *
     * @return void
     *
     * @throws \Exception
     */
    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $confDir = $this->getConfigurationDir();
        $loader->load(
            function (ContainerBuilder $containerBuilder) use ($loader) {
                $this->configureContainer($containerBuilder, $loader);
            }
        );

        if ($confDir === null) {
            return;
        }

        $loader->load($confDir . '/{packages}/*' . self::CONFIG_EXTS, 'glob');
        $loader->load($confDir . '/{packages}/' . $this->environment . '/**/*' . self::CONFIG_EXTS, 'glob');
        $loader->load($confDir . '/{services}' . self::CONFIG_EXTS, 'glob');
        $loader->load($confDir . '/{services}_' . $this->environment . self::CONFIG_EXTS, 'glob');
    }

    /**
     * @param ContainerBuilder $container
     * @param LoaderInterface  $loader
     *
     * @return void
     */
    public function configureContainer(ContainerBuilder $container, LoaderInterface $loader): void
    {
        $container->setParameter('container.dumper.inline_class_loader', true);

        // Configure router
        $container->loadFromExtension('framework', [
            'router' => [
                'resource' => 'kernel::loadRoutes',
                'type'     => 'service',
            ],
        ]);

        if (!$container->hasDefinition('kernel')) {
            $container->register('kernel', static::class)
                ->setSynthetic(true)
                ->setPublic(true);
        }

        $kernelDefinition = $container->getDefinition('kernel');
        $kernelDefinition->addTag('routing.route_loader');

        if ($this instanceof EventSubscriberInterface) {
            $kernelDefinition->addTag('kernel.event_subscriber');
        }

        $container->addObjectResource($this);
    }

    /**
     * @param LoaderInterface $loader
     *
     * @return RouteCollection
     *
     * @throws LoaderLoadException
     */
    public function loadRoutes(LoaderInterface $loader): RouteCollection
    {
        $routes = new RouteCollectionBuilder($loader);
        $confDir = $this->getConfigurationDir();

        if ($confDir === null) {
            return $routes->build();
        }

        $routes->import($confDir . '/{routes}/' . $this->environment . '/**/*' . self::CONFIG_EXTS, '/', 'glob');
        $routes->import($confDir . '/{routes}/*' . self::CONFIG_EXTS, '/', 'glob');
        $routes->import($confDir . '/{routes}' . self::CONFIG_EXTS, '/', 'glob');

        return $routes->build();
    }
}
