<?php declare(strict_types=1);

namespace RichCongress\WebTestBundle\DependencyInjection\CompilerPass;

use RichCongress\BundleToolbox\Configuration\AbstractCompilerPass;
use RichCongress\WebTestBundle\Command\DebugOverridenServicesCommand;
use RichCongress\WebTestBundle\OverrideService\OverrideServiceInterface;
use RichCongress\WebTestBundle\OverrideService\OverrideServiceManager;
use Symfony\Component\DependencyInjection\Compiler\PriorityTaggedServiceTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class OverrideServicesPass
 *
 * @package   RichCongress\WebTestBundle\DependencyInjection\CompilerPass
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 */
class OverrideServicesPass extends AbstractCompilerPass
{
    public const OVERRIDE_SERVICE_TAG = 'rich_congress.web_test.override_service';
    public const PRIORITY = -100;

    use PriorityTaggedServiceTrait;

    /**
     * @param ContainerBuilder $container
     *
     * @return void
     */
    public function process(ContainerBuilder $container): void
    {
        $managerDefinition = $container->findDefinition(OverrideServiceManager::class);
        $commandDefinition = $container->findDefinition(DebugOverridenServicesCommand::class);
        $taggedServices = $this->findAndSortTaggedServices(self::OVERRIDE_SERVICE_TAG, $container);

        foreach ($taggedServices as $service) {
            $definition = $container->findDefinition((string) $service);
            $class = $definition->getClass();
            $reference = new Reference($class);
            static::decorateServices($definition);

            $managerDefinition->addMethodCall('addOverrideService', [$reference]);
            $commandDefinition->addMethodCall('addOverrideServiceClass', [$class]);
        }
    }

    /**
     * @param Definition $definition
     *
     * @return void
     */
    protected static function decorateServices(Definition $definition): void
    {
        /** @var OverrideServiceInterface $class */
        $class = $definition->getClass();

        foreach ($class::getOverridenServiceNames() as $overridenServiceName) {
            $definition->setDecoratedService($overridenServiceName);
        }
    }
}
