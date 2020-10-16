<?php declare(strict_types=1);

namespace RichCongress\WebTestBundle\TestCase\TestTrait;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;

/**
 * Class WebTestAssertionsTrait
 *
 * @package    RichCongress\WebTestBundle\TestCase\TestTrait
 * @author     Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright  2014 - 2020 RichCongress (https://www.richcongress.com)
 */
trait WebTestAssertionsTrait
{
    use \Symfony\Bundle\FrameworkBundle\Test\WebTestAssertionsTrait;

    protected static function assertStatusCode(int $expected, KernelBrowser $kernelBrowser): void
    {
        $response = $kernelBrowser->getResponse();
        $statusCode = $response->getStatusCode();

        self::assertEquals($expected, $statusCode, $response->getContent());
    }
}
