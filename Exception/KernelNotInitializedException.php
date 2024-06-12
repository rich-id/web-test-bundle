<?php declare(strict_types=1);

namespace RichCongress\WebTestBundle\Exception;

/**
 * Class KernelNotInitializedException
 *
 * @package    RichCongress\WebTestBundle\Exception
 * @author     Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright  2014 - 2020 RichCongress (https://www.richcongress.com)
 */
final class KernelNotInitializedException extends AbstractException
{
    /** @var string  */
    protected static $error = "The kernel was not initialized. Did you add the attribute `#[TestConfig('kernel')]` to your method or class?";
}
