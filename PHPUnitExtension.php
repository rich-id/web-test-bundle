<?php declare(strict_types=1);

namespace RichCongress\WebTestBundle;

use PHPUnit\Event\Test\Finished;
use PHPUnit\Event\Test\FinishedSubscriber;
use PHPUnit\Event\Test\PreparationStarted;
use PHPUnit\Event\Test\PreparationStartedSubscriber;
use PHPUnit\Runner\Extension\Extension;
use PHPUnit\Runner\Extension\Facade;
use PHPUnit\Runner\Extension\ParameterCollection;
use PHPUnit\TextUI\Configuration\Configuration;
use RichCongress\WebTestBundle\Doctrine\Driver\StaticDriver;

class PHPUnitExtension implements Extension
{
    public function bootstrap(Configuration $configuration, Facade $facade, ParameterCollection $parameters): void
    {
        $facade->registerSubscriber(new class implements PreparationStartedSubscriber {
            public function notify(PreparationStarted $event): void
            {
                StaticDriver::beginTransaction();
            }
        });

        $facade->registerSubscriber(new class implements FinishedSubscriber {
            public function notify(Finished $event): void
            {
                StaticDriver::rollBack();
            }
        });
    }
}
