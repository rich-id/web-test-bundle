<?php declare(strict_types=1);

namespace RichCongress\WebTestBundle\DependencyInjection\CompilerPass;

use RichCongress\BundleToolbox\Configuration\AbstractCompilerPass;
use RichCongress\WebTestBundle\Doctrine\TestConnectionFactory;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class TestConnectionFactoryPass
 *
 * @package    RichCongress\WebTestBundle\DependencyInjection\CompilerPass
 * @author     Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright  2014 - 2020 RichCongress (https://www.richcongress.com)
 */
final class TestConnectionFactoryPass extends AbstractCompilerPass
{
    public const PRIORITY = -100;
    public const FACTORY_SERVICE_ID = 'doctrine.dbal.connection_factory';

    public function process(ContainerBuilder $container): void
    {
        if (!$container->has(self::FACTORY_SERVICE_ID)) {
            return;
        }

        $definition = new Definition(TestConnectionFactory::class);
        $definition->setDecoratedService(self::FACTORY_SERVICE_ID)
            ->setArgument(
                '$decoratedFactory',
                new Reference(TestConnectionFactory::class . '.inner')
            );

    }
}
