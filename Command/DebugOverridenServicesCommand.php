<?php declare(strict_types=1);

namespace RichCongress\WebTestBundle\Command;

use RichCongress\WebTestBundle\OverrideService\OverrideServiceInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class DebugOverridenServicesCommand
 *
 * @package   RichCongress\WebTestBundle\Command
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 */
#[AsCommand('debug:overriden_services')]
class DebugOverridenServicesCommand extends Command
{
    /** @var array|string[]|OverrideServiceInterface[] */
    protected $overrideServiceClasses = [];

    public function addOverrideServiceClass(string $overrideServiceClass): void
    {
        $this->overrideServiceClasses[] = $overrideServiceClass;
    }

    protected function configure(): void
    {
        $this->setDescription('Prints out all test services that override base services');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $content = \array_map(
            static function (string $overrideServiceClass) {
                $overridenServiceNames = $overrideServiceClass::getOverridenServiceNames();

                return [
                    $overrideServiceClass,
                    \implode("\n", $overridenServiceNames)
                ];
            },
            $this->overrideServiceClasses
        );

        $table = new Table($output);
        $table->setHeaders(['Service classname', 'Overriden services']);
        $table->setRows($content);
        $table->render();

        return 0;
    }
}
