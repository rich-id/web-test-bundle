<?php declare(strict_types=1);

namespace RichCongress\WebTestBundle\Tests\Resources\config;

use RichCongress\WebTestBundle\Kernel\DefaultTestKernel;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class TestKernel extends DefaultTestKernel implements EventSubscriberInterface
{
    public function __construct()
    {
        parent::__construct('web_test_bundle_test', false);
    }

    public function getProjectDir(): string
    {
        return __DIR__ . '/../../../';
    }

    public function getConfigurationDir(): ?string
    {
        return __DIR__;
    }

    public static function getSubscribedEvents(): array
    {
        return [];
    }
}
