<?php declare(strict_types=1);

namespace RichCongress\WebTestBundle\ServiceResolver;

use PhpDocReader\PhpDocReader;
use Psr\Container\ContainerInterface;
use RichCongress\TestTools\CacheTrait\CachedGetterTrait;
use RichCongress\WebTestBundle\TestCase\TestCase;

/**
 * Class PublicPropertyServiceResolver
 *
 * @package    RichCongress\WebTestBundle\ServiceResolver
 * @author     Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright  2014 - 2021 RichCongress (https://www.richcongress.com)
 *
 * @method static PhpDocReader getDocReader()
 */
final class PublicPropertyServiceResolver
{
    use CachedGetterTrait;

    public static function resolve(TestCase $instance, ContainerInterface $container): void
    {
        $reflectionClass = new \ReflectionClass($instance);
        $publicProperties = $reflectionClass->getProperties(\ReflectionProperty::IS_PUBLIC);
        $reader = self::getDocReader();

        foreach ($publicProperties as $property) {
            if ($property->getValue($instance) !== null) {
                continue;
            }

            $serviceName = $reader->getPropertyType($property) ?? $reader->getPropertyClass($property);

            if ($container->has($serviceName)) {
                $service = $container->get($serviceName);
                $property->setValue($instance, $service);
            }
        }
    }

    /** @codeCoverageIgnore  */
    protected static function createDocReader(): PhpDocReader
    {
        return new PhpDocReader();
    }
}
