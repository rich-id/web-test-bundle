<?php declare(strict_types=1);

namespace RichCongress\WebTestBundle\Tests\Resources\Stubs;

use RichCongress\WebTestBundle\Command\DebugOverridenServicesCommand;
use RichCongress\WebTestBundle\OverrideService\OverrideServiceInterface;
use RichCongress\WebTestBundle\OverrideService\OverrideServiceTrait;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class DummyOverrideService
 *
 * @package    RichCongress\WebTestBundle\Tests\Resources\Stubs
 * @author     Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright  2014 - 2020 RichCongress (https://www.richcongress.com)
 */
#[AsCommand('debug:overriden_services')]
final class DummyCommandStub extends DebugOverridenServicesCommand implements OverrideServiceInterface
{
    use OverrideServiceTrait;

    /** @var string stirng */
    protected static $overridenServices = [DebugOverridenServicesCommand::class];

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('This is a stub');

        return $this->innerService->execute($input, $output);
    }
}
