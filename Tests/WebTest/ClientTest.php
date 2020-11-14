<?php declare(strict_types=1);

namespace RichCongress\WebTestBundle\Tests\WebTest;

use RichCongress\WebTestBundle\TestCase\ControllerTestCase;
use RichCongress\WebTestBundle\WebTest\Client;
use RichCongress\WebTestBundle\WebTest\Response;

/**
 * Class ClientTest
 *
 * @package    RichCongress\WebTestBundle\Tests\WebTest
 * @author     Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright  2014 - 2020 RichCongress (https://www.richcongress.com)
 *
 * @covers \RichCongress\WebTestBundle\WebTest\Client
 */
final class ClientTest extends ControllerTestCase
{
    public function testClientGetRequest(): void
    {
        $client = $this->getClient();
        $response = $client->get('/not/an/uri');
        self::assertStatusCode(Response::HTTP_NOT_FOUND, $response);

        $response = $client->getBrowser()->getResponse();
        self::assertStatusCode(Response::HTTP_NOT_FOUND, $response);
    }

    public function testClientPostRequest(): void
    {
        $client = $this->getClient();
        $response = $client->post('/not/an/uri');
        self::assertStatusCode(Response::HTTP_NOT_FOUND, $response);
    }

    public function testClientPutRequest(): void
    {
        $client = $this->getClient();
        $response = $client->put('/not/an/uri');
        self::assertStatusCode(Response::HTTP_NOT_FOUND, $response);
    }

    public function testClientPatchRequest(): void
    {
        $client = $this->getClient();
        $response = $client->patch('/not/an/uri');
        self::assertStatusCode(Response::HTTP_NOT_FOUND, $response);
    }

    public function testClientDeleteRequest(): void
    {
        $client = $this->getClient();
        $response = $client->delete('/not/an/uri');
        self::assertStatusCode(Response::HTTP_NOT_FOUND, $response);
    }

    public function testExtractBrowser(): void
    {
        $client = $this->getClient();
        $kernelBrowser = $client->getBrowser();

        self::assertSame($kernelBrowser, Client::extractBrowser($client));
        self::assertSame($kernelBrowser, Client::extractBrowser($kernelBrowser));
    }

    public function testExtractBrowserWithNoBrowser(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectErrorMessage('Please give either a Client or a KernelBrowser.');

        Client::extractBrowser(null);
    }
}
