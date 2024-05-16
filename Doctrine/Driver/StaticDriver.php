<?php declare(strict_types=1);

namespace RichCongress\WebTestBundle\Doctrine\Driver;

use Doctrine\DBAL\Connection;

/**
 * Class StaticDriver
 *
 * @package    RichCongress\WebTestBundle\Doctrine\Driver
 * @author     Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright  2014 - 2021 RichCongress (https://www.richcongress.com)
 */
class StaticDriver extends \DAMA\DoctrineTestBundle\Doctrine\DBAL\StaticDriver
{
    /** @var bool */
    protected static $isInTransaction = false;

    public static function isInTransaction(): bool
    {
        return static::$isInTransaction;
    }

    public static function beginTransaction(): void
    {
        static::$isInTransaction = true;
        parent::beginTransaction();
    }

    public static function rollBack(): void
    {
        static::$isInTransaction = false;
        parent::rollBack();
    }

    public static function commit(): void
    {
        static::$isInTransaction = false;
        parent::commit();
    }

    public static function forceCommit(): void
    {
        if (static::isInTransaction()) {
            try {
                static::commit();
            } catch (\Throwable $e) {
                // Ignore error because transaction could be already implicitly commited in case of schema changes
            }
            static::beginTransaction();
        }
    }

    public function getName(): string
    {
        return 'Static driver';
    }

    public function getDatabase(Connection $conn): ?string
    {
        return $conn->getDatabase();
    }
}
