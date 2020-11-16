<?php declare(strict_types=1);

namespace RichCongress\WebTestBundle\Exception;

/**
 * Class KernelNotInitializedException
 *
 * @package    RichCongress\WebTestBundle\Exception
 * @author     Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright  2014 - 2020 RichCongress (https://www.richcongress.com)
 */
final class KernelNotInitializedException extends \Exception
{
    /** @var string  */
    protected static $error = 'The kernel was not initialized. Did you add the annotation `@TestConfig("kernel")` to your method or class?';

    /** @var string  */
    protected static $documentation = 'https://github.com/richcongress/web-test-bundle/blob/master/Docs/Exceptions.md#KernelNotInitializedException';

    /**
     * KernelNotInitializedException constructor.
     */
    public function __construct()
    {
        $message = self::$error;
        $message .= "\nCheck the documentation: " . self::$documentation;

        parent::__construct($message);
    }
}
