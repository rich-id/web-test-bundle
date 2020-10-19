<?php declare(strict_types=1);

namespace RichCongress\WebTestBundle\OverrideService;

/**
 * Trait OverrideServiceTrait
 *
 * @package   RichCongress\WebTestBundle\OverrideService
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 */
trait OverrideServiceTrait
{
    /** @var static|object */
    protected $innerService;

    /**
     * @return array
     */
    public static function getOverridenServiceNames(): array
    {
        return isset(static::$overridenServices)
            ? (array) static::$overridenServices
            : ['test'];
    }

    public function setInnerService($service): void
    {
        $this->innerService = $service;
    }
}
