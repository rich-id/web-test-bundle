<?php

namespace RichCongress\WebTestBundle\Kernel;

use DAMA\DoctrineTestBundle\DAMADoctrineTestBundle;
use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use RichCongress\WebTestBundle\RichCongressWebTestBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\AbstractConfigurator;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader as ContainerPhpFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use Symfony\Component\Routing\Loader\PhpFileLoader as RoutingPhpFileLoader;
use Symfony\Component\Routing\RouteCollection;

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
        'RichCongress\RecurrentFixturesTestBundle\RichCongressRecurrentFixturesTestBundle',
    ];

    public function getConfigurationDir(): ?string
    {
        return null;
    }

    public function getBundlesPath(): ?string
    {
        $configDir = $this->getConfigurationDir();

        return $configDir ? $configDir . '/bundles.php' : null;
    }

    public function getCacheDir(): string
    {
        if (isset($_SERVER['APP_CACHE_DIR'])) {
            return $_SERVER['APP_CACHE_DIR'].'/'.$this->environment;
        }

        return parent::getCacheDir();
    }

    public function getBuildDir(): string
    {
        if (isset($_SERVER['APP_BUILD_DIR'])) {
            return $_SERVER['APP_BUILD_DIR'].'/'.$this->environment;
        }

        return parent::getBuildDir();
    }

    public function getLogDir(): string
    {
        return $_SERVER['APP_LOG_DIR'] ?? parent::getLogDir();
    }

    public function registerBundles(): iterable
    {
        foreach (static::$defaultBundles as $class) {
            if (\class_exists($class)) {
                yield new $class();
            }
        }

        $bundlePath = $this->getBundlesPath();

        if ($bundlePath === null) {
            return;
        }

        $contents = require $bundlePath;

        foreach ($contents as $class => $envs) {
            $appropriateEnv = $envs[$this->environment] ?? $envs['all'] ?? false;
            $isAlreadyLoaded = in_array($class, static::$defaultBundles, true);

            if ($appropriateEnv && !$isAlreadyLoaded) {
                yield new $class();
            }
        }
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(function (ContainerBuilder $container) use ($loader) {
            $container->setParameter('container.dumper.inline_class_loader', true);

            $container->loadFromExtension('framework', [
                'router' => [
                    'resource' => 'kernel::loadRoutes',
                    'type' => 'service',
                ],
            ]);

            $container->prependExtensionConfig('doctrine', [
                'dbal' => [
                    'connections' => [
                        'default' => [
                            'use_savepoints' => true
                        ]
                    ]
                ],
            ]);

            $kernelClass = str_contains(static::class, "@anonymous\0") ? parent::class : static::class;

            if (!$container->hasDefinition('kernel')) {
                $container->register('kernel', $kernelClass)
                    ->addTag('controller.service_arguments')
                    ->setAutoconfigured(true)
                    ->setSynthetic(true)
                    ->setPublic(true)
                ;
            }

            $kernelDefinition = $container->getDefinition('kernel');
            $kernelDefinition->addTag('routing.route_loader');

            if ($this instanceof EventSubscriberInterface) {
                $kernelDefinition->addTag('kernel.event_subscriber');
            }

            $container->addObjectResource($this);
            if (($bundlePath = $this->getBundlesPath()) !== null) {
                $container->fileExists($bundlePath);
            }

            $configureContainer = new \ReflectionMethod($this, 'configureContainer');
            $configuratorClass = $configureContainer->getNumberOfParameters() > 0 && ($type = $configureContainer->getParameters()[0]->getType()) instanceof \ReflectionNamedType && !$type->isBuiltin() ? $type->getName() : null;

            if ($configuratorClass && !is_a(ContainerConfigurator::class, $configuratorClass, true)) {
                $configureContainer->getClosure($this)($container, $loader);

                return;
            }

            $file = (new \ReflectionObject($this))->getFileName();
            /* @var ContainerPhpFileLoader $kernelLoader */
            $kernelLoader = $loader->getResolver()->resolve($file);
            $kernelLoader->setCurrentDir(\dirname($file));
            $instanceof = &\Closure::bind(fn &() => $this->instanceof, $kernelLoader, $kernelLoader)();

            $valuePreProcessor = AbstractConfigurator::$valuePreProcessor;
            AbstractConfigurator::$valuePreProcessor = fn ($value) => $this === $value ? new Reference('kernel') : $value;

            try {
                $configureContainer->getClosure($this)(new ContainerConfigurator($container, $kernelLoader, $instanceof, $file, $file, $this->getEnvironment()), $loader, $container);
            } finally {
                $instanceof = [];
                $kernelLoader->registerAliasesForSinglyImplementedInterfaces();
                AbstractConfigurator::$valuePreProcessor = $valuePreProcessor;
            }

            $container->setAlias($kernelClass, 'kernel')->setPublic(true);
        });
    }

    public function loadRoutes(LoaderInterface $loader): RouteCollection
    {
        $file = (new \ReflectionObject($this))->getFileName();
        /* @var RoutingPhpFileLoader $kernelLoader */
        $kernelLoader = $loader->getResolver()->resolve($file, 'php');
        $kernelLoader->setCurrentDir(\dirname($file));
        $collection = new RouteCollection();

        $configureRoutes = new \ReflectionMethod($this, 'configureRoutes');
        $configureRoutes->getClosure($this)(new RoutingConfigurator($collection, $kernelLoader, $file, $file, $this->getEnvironment()));

        foreach ($collection as $route) {
            $controller = $route->getDefault('_controller');

            if (\is_array($controller) && [0, 1] === array_keys($controller) && $this === $controller[0]) {
                $route->setDefault('_controller', ['kernel', $controller[1]]);
            } elseif ($controller instanceof \Closure && $this === ($r = new \ReflectionFunction($controller))->getClosureThis() && !str_contains($r->name, '{closure}')) {
                $route->setDefault('_controller', ['kernel', $r->name]);
            }
        }

        return $collection;
    }

    public function configureContainer(ContainerBuilder $container, LoaderInterface $loader): void
    {
        $configDir = $this->getConfigurationDir();

        if ($configDir === null) {
            return;
        }

        $loader->load($configDir . '/{packages}/*' . self::CONFIG_EXTS, 'glob');
        $loader->load($configDir . '/{packages}/' . $this->environment . '/**/*' . self::CONFIG_EXTS, 'glob');
        $loader->load($configDir . '/{services}' . self::CONFIG_EXTS, 'glob');
        $loader->load($configDir . '/{services}_' . $this->environment . self::CONFIG_EXTS, 'glob');
    }

    public function configureRoutes(RoutingConfigurator $routes): void
    {
        $configDir = $this->getConfigurationDir();

        if ($configDir === null) {
            return;
        }

        $routes->import($configDir . '/{routes}/' . $this->environment . '/**/*' . self::CONFIG_EXTS);
        $routes->import($configDir . '/{routes}/*' . self::CONFIG_EXTS);
        $routes->import($configDir . '/{routes}' . self::CONFIG_EXTS);
    }
}
