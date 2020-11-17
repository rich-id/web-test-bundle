<?php declare(strict_types=1);

namespace RichCongress\WebTestBundle\TestCase\Internal;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use RichCongress\TestFramework\TestConfiguration\TestConfiguration;
use RichCongress\WebTestBundle\Doctrine\DatabaseSchemaInitializer;
use RichCongress\WebTestBundle\Exception\KernelNotInitializedException;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;

/**
 * Class WebTestCase
 *
 * @package   RichCongress\WebTestBundle\TestCase\Internal
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 *
 * @internal
 */
final class WebTestCase extends BaseWebTestCase
{
    public const CONFIG_ENABLE_FLAGS = ['kernel', 'container', 'client'];

    /** @var KernelBrowser */
    private $client;

    /**
     * @internal
     */
    public function setUp(): void
    {
        parent::setUp();

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

    /**
     * @internal
     */
    public function tearDown(): void
    {
        parent::tearDown();
        $this->client = null;
    }

    /**
     * @internal
     */
    public function getCurrentContainer(): ContainerInterface
    {
        if (!self::isEnabled()) {
            throw new KernelNotInitializedException();
        }

        return self::$container;
    }

    /**
     * @internal
     */
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
}
