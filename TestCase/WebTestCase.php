<?php declare(strict_types=1);

namespace RichCongress\WebTestBundle\TestCase;

use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;
use Psr\Container\ContainerInterface;
use RichCongress\WebTestBundle\Exception\ContainerNotEnabledException;
use RichCongress\WebTestBundle\Exception\EntityManagerNotFoundException;
use RichCongress\WebTestBundle\OverrideService\OverrideServiceManager;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class WebTestCase
 *
 * @package   RichCongress\WebTestBundle\TestCase
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 */
class WebTestCase extends BaseWebTestCase
{
    /** @var KernelBrowser */
    private static $client;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        static::$client = static::createClient();
    }

    /**
     * @return void
     */
    public function tearDown(): void
    {
        /** @var OverrideServiceManager $overrideServiceManager */
        $overrideServiceManager = static::$container->get(OverrideServiceManager::class);
        $overrideServiceManager->cleanServices();

        parent::tearDown();
    }

    /**
     * @return ContainerInterface
     */
    protected function getContainer(): ContainerInterface
    {
        return static::$container;
    }

    /**
     * @param string $service
     *
     * @return object|null
     */
    protected function getService(string $service)
    {
        if (static::$client === null) {
            throw new ContainerNotEnabledException();
        }

        return $this->getContainer()->get($service);
    }

    /**
     * Gets the entity manager
     *
     * @return ObjectManager
     */
    protected function getManager(): ObjectManager
    {
        try {
            /** @var ObjectManager $manager */
            $manager = $this->getService('doctrine.orm.default_entity_manager');

            return $manager;
        } catch (\Exception $e) {
            throw new EntityManagerNotFoundException();
        }
    }

    /**
     * @param string $entityClass
     *
     * @return ObjectRepository|null
     */
    protected function getRepository(string $entityClass): ?ObjectRepository
    {
        return $this->getManager()->getRepository($entityClass);
    }

    /**
     * @param string $name
     * @param array  $params
     *
     * @return string
     */
    protected function executeCommand(string $name, array $params = []): string
    {
        $params['command'] = $name;

        /** @var KernelInterface $kernel */
        $kernel = $this->getService('kernel');
        $application = new Application($kernel);
        $command = $application->find($name);

        $commandTester = new CommandTester($command);
        $commandTester->execute($params, [
            'interactive' => false,
            'decorated'   => false,
        ]);

        return $commandTester->getDisplay();
    }
}
