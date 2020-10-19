<?php

namespace RichCongress\WebTestBundle\Exception;

/**
 * Class EntityManagerNotFoundException
 *
 * @package    RichCongress\WebTestBundle\Exception
 * @author     Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright  2014 - 2020 RichCongress (https://www.richcongress.com)
 *
 * @covers \RichCongress\WebTestBundle\Exception\EntityManagerNotFoundException
 */
final class EntityManagerNotFoundException extends \Exception
{
    /** @var string  */
    protected static $error = 'The Entity manager cannot be found. Check your Doctrine documentation.';

    /** @var string  */
    protected static $documentation = 'https://github.com/richcongress/web-test-bundle/blob/master/Docs/Exceptions.md#EntityManagerNotFoundException';

    /**
     * EntityManagerNotFoundException constructor.
     */
    public function __construct()
    {
        $message = self::$error;
        $message .= "\nCheck the documentation: " . self::$documentation;

        parent::__construct($message);
    }
}
