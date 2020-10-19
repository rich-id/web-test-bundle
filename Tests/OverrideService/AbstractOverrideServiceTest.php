<?php declare(strict_types=1);

namespace RichCongress\WebTestBundle\Tests\OverrideService;

use RichCongress\WebTestBundle\Command\DebugOverridenServicesCommand;
use RichCongress\WebTestBundle\TestCase\TestCase;
use RichCongress\WebTestBundle\Tests\Resources\Stubs\DummyCommandStub;

/**
 * Class AbstractOverrideService
 *
 * @package    RichCongress\WebTestBundle\Tests\OverrideService
 * @author     Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright  2014 - 2020 RichCongress (https://www.richcongress.com)
 *
 * @covers \RichCongress\WebTestBundle\OverrideService\AbstractOverrideService
 * @covers \RichCongress\WebTestBundle\OverrideService\OverrideServiceTrait
 */
final class AbstractOverrideServiceTest extends TestCase
{
    public function testGetOverridenServiceNames(): void
    {
        $overridenService = DummyCommandStub::getOverridenServiceNames();

        self::assertEquals([DebugOverridenServicesCommand::class], $overridenService);
    }

    public function testServiceOverridenProperly(): void
    {
        $service = $this->getService(DummyCommandStub::class);
        $innerService = $this->getService(DebugOverridenServicesCommand::class);

        self::assertSame($service, $innerService);
        self::assertInstanceOf(DummyCommandStub::class, $service);
        self::assertInstanceOf(DummyCommandStub::class, $innerService);
    }
}
