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
        parent::rollBack();
        static::$isInTransaction = false;
    }

    public static function commit(): void
    {
        parent::commit();
        static::$isInTransaction = false;
    }

    public static function withoutTransaction(callable $callback): void
    {
        $callback();

        if (static::isInTransaction()) {
            try {
                static::commit();
                static::beginTransaction();
            } catch (\Throwable $e) {
                // Skip
            }
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
