<?php declare(strict_types=1);

namespace RichCongress\WebTestBundle\OverrideService;

/**
 * Class AbstractOverrideService
 *
 * @package   RichCongress\WebTestBundle\OverrideService
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 */
class AbstractOverrideService implements OverrideServiceInterface
{
    /**
     * /!\ Needs to be overriden
     * Name of the service to override
     */
    public static $overridenServices = [];

    use OverrideServiceTrait;

    /**
     * AbstractOverrideService constructor.
     */
    public function __construct()
    {
        $this->clean();
    }
}
