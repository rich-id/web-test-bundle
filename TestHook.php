<?php declare(strict_types=1);

namespace RichCongress\WebTestBundle;

use RichCongress\TestFramework\TestHook\AbstractTestHook;
use RichCongress\WebTestBundle\Doctrine\Driver\StaticDriver;

/**
 * Class TestHook
 *
 * @package   RichCongress\WebTestBundle
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 */
class TestHook extends AbstractTestHook
{
    /**
     * @param string $test
     *
     * @return void
     */
    public function executeBeforeTest(string $test): void
    {
        StaticDriver::beginTransaction();
    }

    /**
     * @param string $test
     * @param float  $time
     *
     * @return void
     */
    public function executeAfterTest(string $test, float $time): void
    {
        StaticDriver::rollBack();
    }
}
