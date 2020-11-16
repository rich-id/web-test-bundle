<?php declare(strict_types=1);

namespace RichCongress\WebTestBundle\Tests\Command;

use RichCongress\TestFramework\TestConfiguration\Annotation\TestConfig;
use RichCongress\WebTestBundle\Command\DebugOverridenServicesCommand;
use RichCongress\WebTestBundle\TestCase\TestCase;
use RichCongress\WebTestBundle\Tests\Resources\Stubs\DummyCommandStub;

/**
 * Class DebugOverridenServicesCommandTest
 *
 * @package    RichCongress\WebTestBundle\Tests\Command
 * @author     Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright  2014 - 2020 RichCongress (https://www.richcongress.com)
 *
 * @covers \RichCongress\WebTestBundle\Command\DebugOverridenServicesCommand
 * @covers \RichCongress\WebTestBundle\TestCase\TestCase
 * @TestConfig("kernel")
 */
final class DebugOverridenServicesCommandTest extends TestCase
{
    public function testExecute(): void
    {
        $output = $this->executeCommand(
            DebugOverridenServicesCommand::getDefaultName()
        );

        self::assertStringContainsString('Service classname', $output);
        self::assertStringContainsString('Overriden services', $output);
        self::assertStringContainsString(DummyCommandStub::class, $output);
        self::assertStringContainsString(DebugOverridenServicesCommand::class, $output);
    }
}
