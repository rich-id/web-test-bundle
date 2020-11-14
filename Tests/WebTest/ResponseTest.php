<?php declare(strict_types=1);

namespace RichCongress\WebTestBundle\Tests\WebTest;

use RichCongress\TestTools\TestCase\TestCase;
use RichCongress\WebTestBundle\WebTest\Response;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

/**
 * Class ResponseTest
 *
 * @package    RichCongress\WebTestBundle\Tests\WebTest
 * @author     Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright  2014 - 2020 RichCongress (https://www.richcongress.com)
 *
 * @covers \RichCongress\WebTestBundle\WebTest\Response
 */
final class ResponseTest extends TestCase
{
    public function testConstruct(): void
    {
        $symfonyResponse = new SymfonyResponse('{"test":"test"}');
        $symfonyResponse->setCharset('test');
        $response = new Response($symfonyResponse);

        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        self::assertEquals($symfonyResponse->getCharset(), $response->getCharset());
    }

    public function testGetJsonContent(): void
    {
        $symfonyResponse = new SymfonyResponse('{"test":"test"}');
        $response = new Response($symfonyResponse);

        self::assertEquals(['test' => 'test'], $response->getJsonContent());
    }
}
