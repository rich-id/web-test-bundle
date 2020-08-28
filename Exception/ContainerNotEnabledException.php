<?php

namespace RichCongress\WebTestBundle\Exception;

use RichCongress\WebTestBundle\TestConfiguration\TestContext;

/**
 * Class ContainerNotEnabledException
 *
 * @package RichCongress\WebTestBundle\Exception
 * @author  Nicolas Guilloux <novares.x@gmail.com>
 */
class ContainerNotEnabledException extends \Exception
{
    protected static $error = 'You did not mentionned that you want to load a container. Add the annotation @WithContainer into the class or test PHP Doc.';
    protected static $documentation = 'https://github.com/richcongress/web-test-bundle/blob/master/Docs/Annotations.md#using-withcontainer';

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
