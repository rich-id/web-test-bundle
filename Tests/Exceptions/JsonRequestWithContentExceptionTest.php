<?php declare(strict_types=1);

namespace RichCongress\WebTestBundle\Tests\Exceptions;

use RichCongress\WebTestBundle\Exception\JsonRequestWithContentException;
use RichCongress\WebTestBundle\TestCase\TestCase;

/**
 * @covers \RichCongress\WebTestBundle\Exception\JsonRequestWithContentException
 * @covers \RichCongress\WebTestBundle\Exception\AbstractException
 */
final class JsonRequestWithContentExceptionTest extends TestCase
{
    public function testException(): void
    {
        $exception = new JsonRequestWithContentException();

        self::assertStringContainsString(
            'You cannot make a json request with both parameters and content set.',
            $exception->getMessage()
        );
    }
}
