<?php declare(strict_types=1);

namespace RichCongress\WebTestBundle\TestCase;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Psr\Container\ContainerInterface;
use RichCongress\WebTestBundle\Exception\EntityManagerNotFoundException;
use RichCongress\WebTestBundle\Exception\KernelNotInitializedException;
use RichCongress\WebTestBundle\ServiceResolver\PublicPropertyServiceResolver;
use RichCongress\WebTestBundle\TestCase\Internal\WebTestCase;
use RichCongress\WebTestBundle\WebTest\Client;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class TestCase
 *
 * @package    RichCongress\WebTestBundle\TestCase
 * @author     Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright  2014 - 2020 RichCongress (https://www.richcongress.com)
 */
abstract class TestCase extends \RichCongress\TestTools\TestCase\TestCase
{
    /** @var WebTestCase */
    private $innerTestCase;

    /**
     * @codeCoverageIgnore
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        $this->innerTestCase = new WebTestCase($name, $data, $dataName);

        parent::__construct($name, $data, $dataName);
    }

    public function setUpTestCase(): void
    {
        parent::setUpTestCase();

        $this->innerTestCase->setUp();

        if (WebTestCase::isEnabled()) {
            PublicPropertyServiceResolver::resolve($this, $this->getContainer());
        }
    }

    public function tearDownTestCase(): void
    {
        $this->innerTestCase->tearDown();
        parent::tearDownTestCase();
    }

    protected function getContainer(): ContainerInterface
    {
        return $this->innerTestCase->getCurrentContainer();
    }

    protected function getClient(): Client
    {
        return new Client($this->innerTestCase->getCurrentClient());
    }

    /**
     * @return object|null
     */
    protected function getService(string $service)
    {
        return $this->getContainer()->get($service);
    }

    protected function getManager(string $name = 'default'): EntityManagerInterface
    {
        try {
            $managerServiceName = sprintf('doctrine.orm.%s_entity_manager', $name);
            /** @var EntityManagerInterface $manager */
            $manager = $this->getService($managerServiceName);

            return $manager;
        } catch (\Throwable $e) {
            if ($e instanceof KernelNotInitializedException) {
                throw $e;
            }

            throw new EntityManagerNotFoundException();
        }
    }

    protected function getRepository(string $entityClass): ?ObjectRepository
    {
        return $this->getManager()->getRepository($entityClass);
    }

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
