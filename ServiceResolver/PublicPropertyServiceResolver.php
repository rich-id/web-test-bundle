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

        foreach ($publicProperties as $property) {
            if (self::getReflectionType($property) !== null) {
                if (method_exists($property, 'isInitialized') && $property->isInitialized($instance)) {
                    continue;
                }
            } elseif ($property->getValue($instance) !== null) {
                continue;
            }

            $serviceName = self::resolveType($property);

            if ($container->has($serviceName)) {
                $service = $container->get($serviceName);
                $property->setValue($instance, $service);
            }
        }
    }

    protected static function resolveType(\ReflectionProperty $reflectionProperty): ?string
    {
        $reflectionType = self::getReflectionType($reflectionProperty);
        $type = $reflectionType ? $reflectionType->getName() : null;

        if ($type !== null) {
            return $type;
        }

        $reader = self::getDocReader();

        return $reader->getPropertyType($reflectionProperty) ?? $reader->getPropertyClass($reflectionProperty);
    }

    protected static function getReflectionType(\ReflectionProperty $reflectionProperty): ?\ReflectionType
    {
        return method_exists($reflectionProperty, 'getType') ? $reflectionProperty->getType() : null;
    }

    /** @codeCoverageIgnore  */
    protected static function createDocReader(): PhpDocReader
    {
        return new PhpDocReader();
    }
}
