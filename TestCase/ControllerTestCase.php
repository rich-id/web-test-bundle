<?php declare(strict_types=1);

namespace RichCongress\WebTestBundle\TestCase;

use RichCongress\WebTestBundle\Exception\CsrfTokenManagerMissingException;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

/**
 * Class ControllerTestCase
 *
 * @package   RichCongress\WebTestBundle\TestCase
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 */
class ControllerTestCase extends WebTestCase
{
    /** @var KernelBrowser */
    private static $client;

    /**
     * @return KernelBrowser
     */
    protected static function getClient(): KernelBrowser
    {
        return static::$client;
    }

    /**
     * Get the CSRF token from the Type and the Client
     *
     * @param string $intention
     *
     * @return string
     */
    public function getCsrfToken(string $intention): string
    {
        /** @var ContainerInterface $container */
        $container = $this->getContainer();
        $class = null;

        try {
            /** @var CsrfTokenManagerInterface $csrfTokenManager */
            $csrfTokenManager = $this->getService('security.csrf.token_manager');
        } catch (\Throwable $e) {
            throw new CsrfTokenManagerMissingException();
        }

        if ($container->has($intention)) {
            $class = $this->getService($intention);
        } elseif (\is_subclass_of($intention, FormTypeInterface::class)) {
            $class = new $intention();
        }

        if ($class !== null) {
            $intention = $class->getBlockPrefix() ?? $intention;
        }

        return (string) $csrfTokenManager->getToken($intention);
    }

    /**
     * @param int           $expected
     * @param KernelBrowser $kernelBrowser
     *
     * @return void
     */
    protected static function assertStatusCode(int $expected, KernelBrowser $kernelBrowser): void
    {
        $response = $kernelBrowser->getResponse();
        $statusCode = $response->getStatusCode();

        self::assertEquals($expected, $statusCode, $response->getContent());
    }

    /**
     * Transform an array of query parameters in a string for URL
     *
     * @param array $queryParams
     *
     * @return string
     */
    public static function parseQueryParams(array $queryParams): string
    {
        return '?' . \http_build_query($queryParams);
    }

    /**
     * Extract Json content from the client
     *
     * @param KernelBrowser $client
     * @param boolean       $assoc
     *
     * @return array|object|null
     */
    public static function getJsonContent(KernelBrowser $client, bool $assoc = true)
    {
        return \json_decode($client->getResponse()->getContent(), $assoc, 512, JSON_THROW_ON_ERROR);
    }
}
