<?php declare(strict_types=1);

namespace RichCongress\WebTestBundle\Tests\Exceptions;

use RichCongress\WebTestBundle\Exception\CsrfTokenManagerMissingException;
use RichCongress\WebTestBundle\TestCase\TestCase;

/**
 * Class CsrfTokenManagerMissingExceptionTest
 *
 * @package    RichCongress\WebTestBundle\Tests\Exceptions
 * @author     Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright  2014 - 2020 RichCongress (https://www.richcongress.com)
 *
 * @covers \RichCongress\WebTestBundle\Exception\CsrfTokenManagerMissingException
 * @covers \RichCongress\WebTestBundle\Exception\AbstractException
 */
final class CsrfTokenManagerMissingExceptionTest extends TestCase
{
    public function testException(): void
    {
        $exception = new CsrfTokenManagerMissingException();

        self::assertStringContainsString(
            'The Security\'s CSRF Token Manager is missing from the container.',
            $exception->getMessage()
        );
    }
}
