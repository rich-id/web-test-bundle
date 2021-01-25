<?php declare(strict_types=1);

namespace RichCongress\WebTestBundle\Tests\TestCase;

use RichCongress\WebTestBundle\Exception\CsrfTokenManagerMissingException;
use RichCongress\WebTestBundle\TestCase\ControllerTestCase;
use RichCongress\WebTestBundle\TestCase\Internal\WebTestCase;
use RichCongress\WebTestBundle\TestCase\TestCase;
use RichCongress\WebTestBundle\Tests\Resources\FormType\DummyFormType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ControllerTestCaseTest
 *
 * @package    RichCongress\WebTestBundle\Tests\TestCase
 * @author     Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright  2014 - 2020 RichCongress (https://www.richcongress.com)
 *
 * @covers \RichCongress\WebTestBundle\TestCase\ControllerTestCase
 * @covers \RichCongress\WebTestBundle\TestCase\TestTrait\ControllerTestUtilitiesTrait
 * @covers \RichCongress\WebTestBundle\TestCase\TestTrait\WebTestAssertionsTrait
 */
final class ControllerTestCaseTest extends ControllerTestCase
{
    /** @var \ReflectionProperty */
    private $innerTestCaseReflection;

    /** @var WebTestCase */
    private $innerTestCaseBackup;

    public function setUp(): void
    {
        parent::setUp();

        $this->innerTestCaseReflection = new \ReflectionProperty(TestCase::class, 'innerTestCase');
        $this->innerTestCaseReflection->setAccessible(true);
        $this->innerTestCaseBackup = $this->innerTestCaseReflection->getValue($this);
    }

    public function tearDown(): void
    {
        $this->innerTestCaseReflection->setValue($this, $this->innerTestCaseBackup);
        $this->innerTestCaseReflection->setAccessible(false);

        parent::tearDown();
    }

    public function testGetCsrfToken(): void
    {
        $csrfToken = $this->getCsrfToken('test');
        self::assertMatchesRegularExpression('/^[\w-]{43}$/', $csrfToken);
    }

    public function testGetCsrfTokenFromClass(): void
    {
        $csrfToken = $this->getCsrfToken(DummyFormType::class);
        self::assertMatchesRegularExpression('/^[\w-]{43}$/', $csrfToken);
    }

    public function testGetCsrfTokenFromService(): void
    {
        $csrfToken = $this->getCsrfToken('test.dummy_form_type');
        self::assertMatchesRegularExpression('/^[\w-]{43}$/', $csrfToken);
    }

    public function testMissingCsrfTokenManager(): void
    {
        $this->expectException(CsrfTokenManagerMissingException::class);
        $this->expectErrorMessage('test');

        $this->innerTestCaseReflection->setValue($this, null);
        $this->getCsrfToken('test');
    }

    public function testParseQueryParams(): void
    {
        $params = [
            'test' => true,
            'test2' => 'string',
            'test3' => ['test3'],
        ];

        $queryParams = self::parseQueryParams($params);
        self::assertEquals('?test=1&test2=string&test3%5B0%5D=test3', $queryParams);
    }

    public function testGetJsonContent(): void
    {
        $client = $this->getClient();
        $response = $client->request('GET', '/not/an/url');
        self::assertStatusCode(Response::HTTP_NOT_FOUND, $response);

        $this->expectException(\JsonException::class);
        self::getJsonContent($client);
    }
}
