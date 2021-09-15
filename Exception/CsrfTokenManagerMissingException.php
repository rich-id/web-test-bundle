<?php

namespace RichCongress\WebTestBundle\Exception;

/**
 * Class CsrfTokenManagerMissingException
 *
 * @package   RichCongress\WebTestBundle\Exception
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 */
final class CsrfTokenManagerMissingException extends AbstractException
{
    /** @var string */
    protected static $error = 'The Security\'s CSRF Token Manager is missing from the container.';
}
