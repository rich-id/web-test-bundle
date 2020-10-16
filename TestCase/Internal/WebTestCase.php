<?php declare(strict_types=1);

namespace RichCongress\WebTestBundle\TestCase\Internal;

use Psr\Container\ContainerInterface;
use RichCongress\WebTestBundle\OverrideService\OverrideServiceManager;
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
    /** @var KernelBrowser */
    private static $client;

    /**
     * @internal
     */
    public function setUp(): void
    {
        self::$client = self::createClient();
    }

    /**
     * @internal
     */
    public function tearDown(): void
    {
        /** @var OverrideServiceManager $overrideServiceManager */
        $overrideServiceManager = $this->getContainer()->get(OverrideServiceManager::class);
        $overrideServiceManager->cleanServices();

        parent::tearDown();
        self::$client = null;
    }

    /**
     * @internal
     */
    public function getContainer(): ContainerInterface
    {
        return self::$container;
    }

    /**
     * @internal
     */
    public function getClient(): KernelBrowser
    {
        return self::$client;
    }
}
