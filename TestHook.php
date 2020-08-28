<?php declare(strict_types=1);

namespace RichCongress\WebTestBundle;

use DAMA\DoctrineTestBundle\PHPUnit\PHPUnitExtension;
use RichCongress\TestFramework\TestHook\AbstractTestHook;

/**
 * Class TestHook
 *
 * @package   RichCongress\WebTestBundle
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 */
class TestHook extends AbstractTestHook
{
    protected $damaPHPUnitExtension;

    /**
     * TestHook constructor.
     */
    public function __construct()
    {
        $this->damaPHPUnitExtension = new PHPUnitExtension();
    }

    /**
     * @return void
     */
    public function executeBeforeFirstTest(): void
    {
        $this->damaPHPUnitExtension->executeBeforeFirstTest();
    }

    /**
     * @param string $test
     *
     * @return void
     */
    public function executeBeforeTest(string $test): void
    {
        $this->damaPHPUnitExtension->executeBeforeTest($test);
    }

    /**
     * @param string $test
     * @param float  $time
     *
     * @return void
     */
    public function executeAfterTest(string $test, float $time): void
    {
        $this->damaPHPUnitExtension->executeAfterTest($test, $time);
    }
}
