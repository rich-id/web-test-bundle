<?php declare(strict_types=1);

namespace RichCongress\WebTestBundle\TestCase\Internal;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use RichCongress\TestFramework\TestConfiguration\TestConfiguration;
use RichCongress\WebTestBundle\Doctrine\DatabaseSchemaInitializer;
use RichCongress\WebTestBundle\Exception\KernelNotInitializedException;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\BrowserKitAssertionsTrait;
use Symfony\Bundle\FrameworkBundle\Test\DomCrawlerAssertionsTrait;
use Symfony\Bundle\FrameworkBundle\Test\HttpClientAssertionsTrait;
use Symfony\Bundle\FrameworkBundle\Test\MailerAssertionsTrait;
use Symfony\Bundle\FrameworkBundle\Test\NotificationAssertionsTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestAssertionsTrait;
use Symfony\Component\BrowserKit\AbstractBrowser;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Contracts\Service\ResetInterface;

/** @internal */
final class WebTestCase
{
    public const CONFIG_ENABLE_FLAGS = ['kernel', 'container', 'client', 'fixtures'];

    protected static ?KernelInterface $kernel = null;
    protected static ?string $class = null;
    protected static bool $booted = false;

    private ?KernelBrowser $client = null;

    public function setUp(): void
    {
        if (!self::isEnabled()) {
            return;
        }

        $this->client = self::createClient();
        $container = $this->getCurrentContainer();

        if ($container->has(EntityManagerInterface::class)) {
            $entityManager = $container->get(EntityManagerInterface::class);
            DatabaseSchemaInitializer::init($entityManager);
        }
    }

    public function tearDown(): void
    {
        static::ensureKernelShutdown();
        static::$class = null;
        static::$kernel = null;
        static::$booted = false;

        self::getClient(null);

        $this->client = null;
    }

    public function getCurrentContainer(): ContainerInterface
    {
        if (!self::isEnabled()) {
            throw new KernelNotInitializedException();
        }

        return self::getContainer();
    }

    public function getCurrentClient(): KernelBrowser
    {
        if (!self::isEnabled()) {
            throw new KernelNotInitializedException();
        }

        return $this->client;
    }

    public static function isEnabled(): bool
    {
        foreach (self::CONFIG_ENABLE_FLAGS as $flag) {
            if (TestConfiguration::get($flag) === true) {
                return true;
            }
        }

        return false;
    }

    /**
     * Creates a KernelBrowser.
     *
     * @param array $options An array of options to pass to the createKernel method
     * @param array $server  An array of server parameters
     */
    protected static function createClient(array $options = [], array $server = []): KernelBrowser
    {
        if (static::$booted) {
            throw new \LogicException(\sprintf('Booting the kernel before calling "%s()" is not supported, the kernel should only be booted once.', __METHOD__));
        }

        $kernel = static::bootKernel($options);

        try {
            $client = $kernel->getContainer()->get('test.client');
        } catch (ServiceNotFoundException) {
            if (class_exists(KernelBrowser::class)) {
                throw new \LogicException('You cannot create the client used in functional tests if the "framework.test" config is not set to true.');
            }
            throw new \LogicException('You cannot create the client used in functional tests if the BrowserKit component is not available. Try running "composer require symfony/browser-kit".');
        }

        $client->setServerParameters($server);

        return self::getClient($client);
    }

    /**
     * @throws \RuntimeException
     * @throws \LogicException
     */
    protected static function getKernelClass(): string
    {
        if (!isset($_SERVER['KERNEL_CLASS']) && !isset($_ENV['KERNEL_CLASS'])) {
            throw new \LogicException(\sprintf('You must set the KERNEL_CLASS environment variable to the fully-qualified class name of your Kernel in phpunit.xml / phpunit.xml.dist or override the "%1$s::createKernel()" or "%1$s::getKernelClass()" method.', static::class));
        }

        if (!class_exists($class = $_ENV['KERNEL_CLASS'] ?? $_SERVER['KERNEL_CLASS'])) {
            throw new \RuntimeException(\sprintf('Class "%s" doesn\'t exist or cannot be autoloaded. Check that the KERNEL_CLASS value in phpunit.xml matches the fully-qualified class name of your Kernel or override the "%s::createKernel()" method.', $class, static::class));
        }

        return $class;
    }

    /**
     * Boots the Kernel for this test.
     */
    protected static function bootKernel(array $options = []): KernelInterface
    {
        static::ensureKernelShutdown();

        $kernel = static::createKernel($options);
        $kernel->boot();
        static::$kernel = $kernel;
        static::$booted = true;

        return static::$kernel;
    }

    /**
     * Provides a dedicated test container with access to both public and private
     * services. The container will not include private services that have been
     * inlined or removed. Private services will be removed when they are not
     * used by other services.
     *
     * Using this method is the best way to get a container from your test code.
     *
     * @return Container
     */
    protected static function getContainer(): \Symfony\Component\DependencyInjection\ContainerInterface
    {
        if (!static::$booted) {
            static::bootKernel();
        }

        try {
            return self::$kernel->getContainer()->get('test.service_container');
        } catch (ServiceNotFoundException $e) {
            throw new \LogicException('Could not find service "test.service_container". Try updating the "framework.test" config to "true".', 0, $e);
        }
    }

    /**
     * Creates a Kernel.
     *
     * Available options:
     *
     *  * environment
     *  * debug
     */
    protected static function createKernel(array $options = []): KernelInterface
    {
        static::$class ??= static::getKernelClass();

        $env = $options['environment'] ?? $_ENV['APP_ENV'] ?? $_SERVER['APP_ENV'] ?? 'test';
        $debug = $options['debug'] ?? $_ENV['APP_DEBUG'] ?? $_SERVER['APP_DEBUG'] ?? true;

        return new static::$class($env, $debug == true);
    }

    /**
     * Shuts the kernel down if it was used in the test - called by the tearDown method by default.
     */
    protected static function ensureKernelShutdown()
    {
        if (null !== static::$kernel) {
            static::$kernel->boot();
            $container = static::$kernel->getContainer();

            if ($container->has('services_resetter')) {
                // Instantiate the service because Container::reset() only resets services that have been used
                $container->get('services_resetter');
            }

            static::$kernel->shutdown();
            static::$booted = false;

            if ($container instanceof ResetInterface) {
                $container->reset();
            }
        }
    }

    protected static function getClient(?AbstractBrowser $newClient = null): ?AbstractBrowser
    {
        static $client;

        if (0 < \func_num_args()) {
            return $client = $newClient;
        }

        if (!$client instanceof AbstractBrowser) {
            static::fail(\sprintf('A client must be set to make assertions on it. Did you forget to call "%s::createClient()"?', __CLASS__));
        }

        return $client;
    }
}
