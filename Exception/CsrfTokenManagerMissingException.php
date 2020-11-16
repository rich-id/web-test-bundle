<?php

namespace RichCongress\WebTestBundle\Exception;

/**
 * Class CsrfTokenManagerMissingException
 *
 * @package   RichCongress\WebTestBundle\Exception
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 */
final class CsrfTokenManagerMissingException extends \Exception
{
    /** @var string */
    protected static $error = 'The Security\'s CSRF Token Manager is missing from the container.';

    /** @var string  */
    protected static $documentation = 'https://github.com/richcongress/web-test-bundle/blob/master/Docs/Exceptions.md#CsrfTokenManagerMissingException';

    /**
     * CsrfTokenManagerMissingException constructor.
     */
    public function __construct()
    {
        $message = self::$error;
        $message .= "\nCheck the documentation: " . self::$documentation;

        parent::__construct($message);
    }
}
