<?php declare(strict_types=1);

namespace RichCongress\WebTestBundle\Tests\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use RichCongress\WebTestBundle\Doctrine\DatabaseSchemaInitializer;
use RichCongress\WebTestBundle\TestCase\TestCase;

/**
 * Class DatabaseSchemaInitializerTest
 *
 * @package    RichCongress\WebTestBundle\Tests\Doctrine
 * @author     Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright  2014 - 2020 RichCongress (https://www.richcongress.com)
 *
 * @covers \RichCongress\WebTestBundle\Doctrine\DatabaseSchemaInitializer
 */
final class DatabaseSchemaInitializerTest extends TestCase
{
    public function testIsAlreadyInitialized(): void
    {
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $this->getManager();
        self::assertInstanceOf(EntityManagerInterface::class, $entityManager);

        self::assertTrue(DatabaseSchemaInitializer::isInitialized($entityManager));
        DatabaseSchemaInitializer::init($entityManager);
    }

    public function testInitialization(): void
    {
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $this->getService('doctrine.orm.aux_entity_manager');
        self::assertInstanceOf(EntityManagerInterface::class, $entityManager);

        self::assertFalse(DatabaseSchemaInitializer::isInitialized($entityManager));
        DatabaseSchemaInitializer::init($entityManager);
    }
}
