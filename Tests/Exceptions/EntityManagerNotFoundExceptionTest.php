<?php declare(strict_types=1);

namespace RichCongress\WebTestBundle\Tests\Exceptions;

use RichCongress\WebTestBundle\Exception\EntityManagerNotFoundException;
use RichCongress\WebTestBundle\TestCase\TestCase;

/**
 * Class EntityManagerNotFoundExceptionTest
 *
 * @package    RichCongress\WebTestBundle\Tests\Exceptions
 * @author     Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright  2014 - 2020 RichCongress (https://www.richcongress.com)
 *
 * @covers \RichCongress\WebTestBundle\Exception\EntityManagerNotFoundException
 * @covers \RichCongress\WebTestBundle\Exception\AbstractException
 */
final class EntityManagerNotFoundExceptionTest extends TestCase
{
    public function testException(): void
    {
        $exception = new EntityManagerNotFoundException();

        self::assertStringContainsString(
            'The Entity manager cannot be found. Check your Doctrine documentation.',
            $exception->getMessage()
        );
    }
}
