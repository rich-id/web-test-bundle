<?php declare(strict_types=1);

namespace RichCongress\WebTestBundle\Exception;

abstract class AbstractException extends \LogicException
{
    /** @var string */
    protected static $error;

    public function __construct()
    {
        $message = static::$error;
        $message .= "\n";
        $message .= 'Check the documentation: ';
        $message .= 'https://github.com/richcongress/web-test-bundle/blob/master/Docs/Exceptions.md#';
        $message .= static::class;

        parent::__construct($message);
    }

    public static function throw(): void
    {
        throw new static();
    }
}
