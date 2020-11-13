<?php declare(strict_types=1);

namespace RichCongress\WebTestBundle\Tests\TestCase;

use Doctrine\ORM\EntityManager;
use RichCongress\TestTools\Helper\ForceExecutionHelper;
use RichCongress\WebTestBundle\Exception\EntityManagerNotFoundException;
use RichCongress\WebTestBundle\TestCase\Internal\WebTestCase;
use RichCongress\WebTestBundle\TestCase\TestCase;
use RichCongress\WebTestBundle\Tests\Resources\Entity\DummyEntity;
use RichCongress\WebTestBundle\Tests\Resources\Repository\DummyEntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

/**
 * Class TestCaseTest
 *
 * @package    RichCongress\WebTestBundle\Tests\TestCase
 * @author     Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright  2014 - 2020 RichCongress (https://www.richcongress.com)
 *
 * @covers RichCongress\WebTestBundle\TestCase\Internal\WebTestCase
 * @covers \RichCongress\WebTestBundle\TestCase\TestCase
 */
final class TestCaseTest extends TestCase
{
    /** @var \ReflectionProperty */
    private $innerTestCaseReflection;

    /** @var WebTestCase */
    private $innerTestCaseBackup;

    public function setUp(): void
    {
        parent::setUp();

        $this->innerTestCaseReflection = new \ReflectionProperty(TestCase::class, 'innerTestCase');
        $this->innerTestCaseReflection->setAccessible(true);
        $this->innerTestCaseBackup = $this->innerTestCaseReflection->getValue($this);
    }

    public function tearDown(): void
    {
        $this->innerTestCaseReflection->setValue($this, $this->innerTestCaseBackup);
        $this->innerTestCaseReflection->setAccessible(false);

        parent::tearDown();
    }

    public function testGetters(): void
    {
        // Check if no error is thrown
        $this->getClient();

        self::assertInstanceOf(EntityManager::class, $this->getManager());
        self::assertInstanceOf(DummyEntityRepository::class, $this->getRepository(DummyEntity::class));
    }

    public function testGetManagerFailure(): void
    {
        $this->innerTestCaseReflection->setValue($this, null);

        $this->expectException(EntityManagerNotFoundException::class);
        $this->expectErrorMessage('The Entity manager cannot be found. Check your Doctrine documentation.');

        $this->getManager();
    }
}
