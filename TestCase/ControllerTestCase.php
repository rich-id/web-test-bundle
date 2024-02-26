<?php declare(strict_types=1);

namespace RichCongress\WebTestBundle\TestCase;

use RichCongress\TestFramework\TestConfiguration\Attribute\TestConfig;
use RichCongress\WebTestBundle\TestCase\TestTrait\ControllerTestUtilitiesTrait;
use RichCongress\WebTestBundle\TestCase\TestTrait\WebTestAssertionsTrait;

/**
 * Class ControllerTestCase
 *
 * @package   RichCongress\WebTestBundle\TestCase
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 */
#[TestConfig('kernel')]
abstract class ControllerTestCase extends TestCase
{
    use WebTestAssertionsTrait;
    use ControllerTestUtilitiesTrait;
}
