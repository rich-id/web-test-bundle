<?php declare(strict_types=1);

namespace RichCongress\WebTestBundle\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;

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
        $key = self::getKey($entityManager);
        $schemaTool = new SchemaTool($entityManager);
        $metadatas = $entityManager->getMetadataFactory()->getAllMetadata();

        $schemaTool->createSchema($metadatas);
        self::$initializedEntityManagers[$key] = true;
    }

    public static function isInitialized(EntityManagerInterface $entityManager): bool
    {
        $key = self::getKey($entityManager);

        return (self::$initializedEntityManagers[$key] ?? false) === true;
    }

    private static function getKey(EntityManagerInterface $entityManager): string
    {
        return spl_object_hash($entityManager);
    }
}
