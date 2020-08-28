<?php declare(strict_types=1);

namespace RichCongress\WebTestBundle\OverrideService;

/**
 * Interface OverrideServiceInterface
 *
 * @package   RichCongress\WebTestBundle\OverrideService
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 */
interface OverrideServiceInterface
{
    /**
     * @return array
     */
    public static function getOverridenServiceNames(): array;

    /**
     * Here reset all variables to their original state
     *
     * @return void
     */
    public function clean(): void;
}
