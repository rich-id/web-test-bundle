<?php declare(strict_types=1);

namespace RichCongress\WebTestBundle\DependencyInjection;

use RichCongress\BundleToolbox\Configuration\AbstractExtension;
use RichCongress\WebTestBundle\DependencyInjection\CompilerPass\OverrideServicesPass;
use RichCongress\WebTestBundle\OverrideService\OverrideServiceInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class RichCongressWebTestExtension extends AbstractExtension implements PrependExtensionInterface
{
    /**
     * @param ContainerBuilder $container
     *
     * @return void
     */
    public function prepend(ContainerBuilder $container): void
    {
        $container->prependExtensionConfig(
            'dama_doctrine_test',
            [
                'enable_static_connection' => true,
                'enable_static_meta_data_cache' => true,
                'enable_static_query_cache' => true,
            ]
        );

    }

    /**
     * @param array            $configs
     * @param ContainerBuilder $container
     *
     * @return void
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources'));
        $loader->load('services.xml');

        $container->registerForAutoconfiguration(OverrideServiceInterface::class)->addTag(OverrideServicesPass::OVERRIDE_SERVICE_TAG);
    }
}
