<?php declare(strict_types=1);

namespace RichCongress\WebTestBundle\Command;

use RichCongress\WebTestBundle\OverrideService\OverrideServiceInterface;
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
class DebugOverridenServicesCommand extends Command
{
    public static $defaultName = 'debug:overridden_services';

    /** @var array|string[]|OverrideServiceInterface[] */
    protected $overrideServiceClasses = [];

    /**
     * @param string $overrideServiceClass
     *
     * @return void
     */
    public function addOverrideServiceClass(string $overrideServiceClass): void
    {
        $this->overrideServiceClasses[] = $overrideServiceClass;
    }

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setDescription('Prints out all test services that override base services');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
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
    }
}
