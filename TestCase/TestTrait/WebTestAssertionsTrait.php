<?php declare(strict_types=1);

namespace RichCongress\WebTestBundle\TestCase\TestTrait;

use Symfony\Component\HttpFoundation\Response;

/**
 * Class WebTestAssertionsTrait
 *
 * @package    RichCongress\WebTestBundle\TestCase\TestTrait
 * @author     Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright  2014 - 2020 RichCongress (https://www.richcongress.com)
 */
trait WebTestAssertionsTrait
{
    protected static function assertStatusCode(int $expected, Response $response): void
    {
        self::assertEquals($expected, $response->getStatusCode(), $response->getContent());
    }
}
