<?php

namespace RichCongress\WebTestBundle\Exception;

/**
 * Class EntityManagerNotFoundException
 *
 * @package RichCongress\WebTestBundle\Exception
 * @author  Nicolas Guilloux <novares.x@gmail.com>
 */
class EntityManagerNotFoundException extends \Exception
{
    protected static $error = 'The Entity manager cannot be found. Check your Doctrine documentation.';
    protected static $documentation = 'https://github.com/richcongress/web-test-bundle/blob/master/Docs/Exceptions.md';

    /**
     * EntityManagerNotFoundException constructor.
     */
    public function __construct()
    {
        $message = static::$error;
        $message .= "\nCheck the documentation: " . static::$documentation;

        parent::__construct($message);
    }
}
