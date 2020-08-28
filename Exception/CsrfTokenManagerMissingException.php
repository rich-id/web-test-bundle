<?php

namespace RichCongress\WebTestBundle\Exception;

/**
 * Class CsrfTokenManagerMissingException
 *
 * @package   RichCongress\WebTestBundle\Exception
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 */
class CsrfTokenManagerMissingException extends \Exception
{
    /**
     * @var string
     */
    protected static $error = 'The Security\'s CSRF Token Manager is missing from the container.';

    /**
     * @var string
     */
    protected static $documentation = 'https://symfony.com/doc/current/security/csrf.html';

    /**
     * ContainerNotEnabledException constructor.
     */
    protected function __construct()
    {
        $message = static::$error;
        $message .= "\nCheck the documentation: " . static::$documentation;

        parent::__construct($message);
    }
}
