<?php declare(strict_types=1);

namespace RichCongress\WebTestBundle\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use RichCongress\WebTestBundle\Doctrine\Driver\StaticDriver;

/**
 * Class DatabaseSchemaFactory
 *
 * @package    RichCongress\WebTestBundle\Doctrine
 * @author     Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright  2014 - 2020 RichCongress (https://www.richcongress.com)
 */
final class DatabaseSchemaInitializer
{
    /** @var array<string, bool> */
    private static $initializedEntityManagers = [];

    public static function init(EntityManagerInterface $entityManager): void
    {
        if (self::isInitialized($entityManager)) {
            return;
        }

        $metadatas = $entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool = new SchemaTool($entityManager);

        $schemaTool->dropDatabase();
        $schemaTool->createSchema($metadatas);
        StaticDriver::forceCommit();

        $key = self::getKey($entityManager);
        self::$initializedEntityManagers[$key] = true;
    }

    public static function isInitialized(EntityManagerInterface $entityManager): bool
    {
        $key = self::getKey($entityManager);

        return (self::$initializedEntityManagers[$key] ?? false) === true;
    }

    protected static function getKey(EntityManagerInterface $entityManager): string
    {
        $serializedParams = serialize($entityManager->getConnection()->getParams());

        return md5($serializedParams);
    }
}
