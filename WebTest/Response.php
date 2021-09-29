<?php declare(strict_types=1);

namespace RichCongress\WebTestBundle\WebTest;

use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

/**
 * Class Response
 *
 * @package    RichCongress\WebTestBundle\WebTest
 * @author     Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright  2014 - 2020 RichCongress (https://www.richcongress.com)
 */
class Response extends SymfonyResponse
{
    public function __construct(SymfonyResponse $response)
    {
        parent::__construct(
            $response->content,
            $response->getStatusCode(),
            $response->headers->all()
        );

        $charset = $response->getCharset();

        if ($charset !== null) {
            $this->setCharset($charset);
        }
    }

    public function getJsonContent(bool $assoc = true)
    {
        return \json_decode(
            $this->getContent(),
            $assoc,
            512,
            JSON_THROW_ON_ERROR
        );
    }
}
