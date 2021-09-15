<?php

namespace RichCongress\WebTestBundle\Exception;

/**
 * Class EntityManagerNotFoundException
 *
 * @package    RichCongress\WebTestBundle\Exception
 * @author     Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright  2014 - 2020 RichCongress (https://www.richcongress.com)
 */
final class EntityManagerNotFoundException extends AbstractException
{
    /** @var string  */
    protected static $error = 'The Entity manager cannot be found. Check your Doctrine documentation.';
}
