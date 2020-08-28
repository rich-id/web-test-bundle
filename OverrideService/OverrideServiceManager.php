<?php declare(strict_types=1);

namespace RichCongress\WebTestBundle\OverrideService;

/**
 * Class OverrideServiceManager
 *
 * @package   RichCongress\WebTestBundle\OverrideService
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 */
class OverrideServiceManager
{
    /** @var array|OverrideServiceInterface[] */
    protected $overrideServices = [];

    /**
     * @param OverrideServiceInterface $overrideService
     *
     * @return void
     */
    public function addOverrideService(OverrideServiceInterface $overrideService): void
    {
        $this->overrideServices[] = $overrideService;
    }

    /**
     * @return void
     */
    public function cleanServices(): void
    {
        foreach ($this->overrideServices as $overrideService) {
            $overrideService->clean();
        }
    }
}
