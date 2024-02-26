<?php declare(strict_types=1);

namespace RichCongress\WebTestBundle\Tests\Doctrine;

use RichCongress\TestFramework\TestConfiguration\Attribute\TestConfig;
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
#[TestConfig('kernel')]
final class DatabaseSchemaInitializerTest extends TestCase
{
    public function testIsAlreadyInitialized(): void
    {
        $entityManager = $this->getManager();
        self::assertTrue(DatabaseSchemaInitializer::isInitialized($entityManager));

        DatabaseSchemaInitializer::init($entityManager);
    }

    public function testInitialization(): void
    {
        $entityManager = $this->getManager('aux');
        self::assertFalse(DatabaseSchemaInitializer::isInitialized($entityManager));

        DatabaseSchemaInitializer::init($entityManager);
    }
}
