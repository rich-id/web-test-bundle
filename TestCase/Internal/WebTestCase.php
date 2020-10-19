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
    private $client;

    /**
     * @internal
     */
    public function setUp(): void
    {
        $this->client = self::createClient();
    }

    /**
     * @internal
     */
    public function tearDown(): void
    {
        /** @var OverrideServiceManager $overrideServiceManager */
        $overrideServiceManager = $this->getCurrentContainer()->get(OverrideServiceManager::class);
        $overrideServiceManager->cleanServices();

        parent::tearDown();
        $this->client = null;
    }

    /**
     * @internal
     */
    public function getCurrentContainer(): ContainerInterface
    {
        return self::$container;
    }

    /**
     * @internal
     */
    public function getCurrentClient(): KernelBrowser
    {
        return $this->client;
    }
}
