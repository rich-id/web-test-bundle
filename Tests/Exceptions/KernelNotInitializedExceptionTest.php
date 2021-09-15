<?php declare(strict_types=1);

namespace RichCongress\WebTestBundle\Tests\Exceptions;

use RichCongress\WebTestBundle\Exception\KernelNotInitializedException;
use RichCongress\WebTestBundle\TestCase\TestCase;

/**
 * Class KernelNotInitializedExceptionTest
 *
 * @package    RichCongress\WebTestBundle\Tests\Exceptions
 * @author     Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright  2014 - 2020 RichCongress (https://www.richcongress.com)
 *
 * @covers \RichCongress\WebTestBundle\Exception\KernelNotInitializedException
 * @covers \RichCongress\WebTestBundle\Exception\AbstractException
 */
final class KernelNotInitializedExceptionTest extends TestCase
{
    public function testException(): void
    {
        $exception = new KernelNotInitializedException();

        self::assertStringContainsString(
            'The kernel was not initialized. Did you add the annotation `@TestConfig("kernel")` to your method or class?',
            $exception->getMessage()
        );
    }
}
