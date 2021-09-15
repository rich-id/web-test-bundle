<?php declare(strict_types=1);

namespace RichCongress\WebTestBundle\Exception;

class JsonRequestWithContentException extends AbstractException
{
    /** @var string  */
    protected static $error = 'You cannot make a json request with both parameters and content set.';
}
