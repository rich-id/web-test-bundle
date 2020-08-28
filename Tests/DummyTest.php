<?php declare(strict_types=1);

namespace RichCongress\WebTestBundle\Tests;

use RichCongress\WebTestBundle\TestCase\TestCase;
use RichCongress\WebTestBundle\RichCongressWebTestBundle;

/**
 * Class DummyTest
 *
 * @package   RichCongress\WebTestBundle\Tests
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 */
class DummyTest extends TestCase
{
    public function testInstanciateBundle(): void
    {
        $bundle = new RichCongressWebTestBundle();

        self::assertInstanceOf(RichCongressWebTestBundle::class, $bundle);
    }
}
