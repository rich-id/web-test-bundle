<?php declare(strict_types=1);

namespace RichCongress\WebTestBundle\WebTest;

use Psr\Container\ContainerInterface;
use RichCongress\WebTestBundle\Exception\JsonRequestWithContentException;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class Client
 *
 * @package    RichCongress\WebTestBundle\WebTest
 * @author     Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright  2014 - 2020 RichCongress (https://www.richcongress.com)
 */
final class Client
{
    /** @var KernelBrowser */
    private $browser;

    public function __construct(KernelBrowser $kernelBrowser)
    {
        $this->browser = $kernelBrowser;
    }

    public function getBrowser(): KernelBrowser
    {
        return $this->browser;
    }

    public function request(
        string $method,
        string $uri,
        array $parameters = [],
        array $files = [],
        array $server = [],
        string $content = null,
        bool $changeHistory = true,
        bool $isJson = true
    ): Response {
        if ($isJson) {
            if ($content !== null) {
                JsonRequestWithContentException::throw();
            }

            $content = json_encode($parameters, JSON_THROW_ON_ERROR);
            $parameters = [];
            $server['CONTENT_TYPE'] = 'application/json';
            $server['HTTP_ACCEPT'] = 'application/json';
        }

        $this->browser->request(
            $method,
            $uri,
            $parameters,
            $files,
            $server,
            $content,
            $changeHistory
        );

        return new Response($this->browser->getResponse());
    }

    public function get(
        string $uri,
        array $queryParams = [],
        array $parameters = [],
        array $files = [],
        array $server = [],
        string $content = null,
        bool $changeHistory = true,
        bool $isJson = true
    ): Response {
        return $this->request(
            'GET',
            $uri . '?' . http_build_query($queryParams),
            $parameters,
            $files,
            $server,
            $content,
            $changeHistory,
            $isJson
        );
    }

    public function post(
        string $uri,
        array $queryParams = [],
        array $parameters = [],
        array $files = [],
        array $server = [],
        string $content = null,
        bool $changeHistory = true,
        bool $isJson = true
    ): Response {
        return $this->request(
            'POST',
            $uri . '?' . http_build_query($queryParams),
            $parameters,
            $files,
            $server,
            $content,
            $changeHistory,
            $isJson
        );
    }

    public function put(
        string $uri,
        array $queryParams = [],
        array $parameters = [],
        array $files = [],
        array $server = [],
        string $content = null,
        bool $changeHistory = true,
        bool $isJson = true
    ): Response {
        return $this->request(
            'PUT',
            $uri . '?' . http_build_query($queryParams),
            $parameters,
            $files,
            $server,
            $content,
            $changeHistory,
            $isJson
        );
    }

    public function patch(
        string $uri,
        array $queryParams = [],
        array $parameters = [],
        array $files = [],
        array $server = [],
        string $content = null,
        bool $changeHistory = true,
        bool $isJson = true
    ): Response {
        return $this->request(
            'PATCH',
            $uri . '?' . http_build_query($queryParams),
            $parameters,
            $files,
            $server,
            $content,
            $changeHistory,
            $isJson
        );
    }

    public function delete(
        string $uri,
        array $queryParams = [],
        array $parameters = [],
        array $files = [],
        array $server = [],
        string $content = null,
        bool $changeHistory = true,
        bool $isJson = true
    ): Response {
        return $this->request(
            'DELETE',
            $uri . '?' . http_build_query($queryParams),
            $parameters,
            $files,
            $server,
            $content,
            $changeHistory,
            $isJson
        );
    }

    /**
     * @param KernelBrowser|Client $client
     */
    public static function extractBrowser($client): KernelBrowser
    {
        if ($client instanceof self) {
            $client = $client->getBrowser();
        }

        if (!$client instanceof KernelBrowser) {
            throw new \LogicException('Please give either a Client or a KernelBrowser.');
        }

        return $client;
    }
}
