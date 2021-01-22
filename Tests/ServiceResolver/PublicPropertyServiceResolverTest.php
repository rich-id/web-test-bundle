<?php declare(strict_types=1);

namespace RichCongress\WebTestBundle\Tests\ServiceResolver;

use PhpDocReader\PhpDocReader;
use RichCongress\TestFramework\TestConfiguration\Annotation\TestConfig;
use RichCongress\WebTestBundle\ServiceResolver\PublicPropertyServiceResolver;
use RichCongress\WebTestBundle\TestCase\TestCase;
use RichCongress\WebTestBundle\Tests\Resources\Repository\DummyEntityRepository;
use RichCongress\WebTestBundle\Tests\Resources\Stubs\DummyCommandStub;

/**
 * Class PublicPropertyServiceResolverTest
 *
 * @package    RichCongress\WebTestBundle\Tests\ServiceResolver
 * @author     Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright  2014 - 2021 RichCongress (https://www.richcongress.com)
 *
 * @covers \RichCongress\WebTestBundle\ServiceResolver\PublicPropertyServiceResolver
 * @TestConfig("kernel")
 */
final class PublicPropertyServiceResolverTest extends TestCase
{
    /** @var DummyCommandStub */
    public $knownService;

    /** @var DummyCommandStub */
    public $alreadyInitializedProperty = false;

    /** @var DummyEntityRepository */
    public static $staticProperty;

    /** @var DummyEntityRepository */
    protected $protectedProperty;

    public function testReader(): void
    {
        self::assertInstanceOf(PhpDocReader::class, PublicPropertyServiceResolver::getDocReader());
    }

    public function testAutowiring(): void
    {
        self::assertInstanceOf(DummyCommandStub::class, $this->knownService);
        self::assertFalse($this->alreadyInitializedProperty);
        self::assertInstanceOf(DummyEntityRepository::class, self::$staticProperty);
        self::assertNull($this->protectedProperty);
    }
}
