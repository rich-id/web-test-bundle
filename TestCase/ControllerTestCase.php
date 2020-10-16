<?php declare(strict_types=1);

namespace RichCongress\WebTestBundle\TestCase;

use RichCongress\WebTestBundle\Exception\CsrfTokenManagerMissingException;
use RichCongress\WebTestBundle\TestCase\TestTrait\WebTestAssertionsTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

/**
 * Class ControllerTestCase
 *
 * @package   RichCongress\WebTestBundle\TestCase
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 */
abstract class ControllerTestCase extends TestCase
{
    use WebTestAssertionsTrait;

    /**
     * Get the CSRF token from the Type and the Client
     */
    protected function getCsrfToken(string $intention): string
    {
        $container = $this->getContainer();
        $class = null;

        try {
            /** @var CsrfTokenManagerInterface $csrfTokenManager */
            $csrfTokenManager = $this->getService(CsrfTokenManagerInterface::class);
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
     * Transform an array of query parameters in a string for URL
     */
    protected static function parseQueryParams(array $queryParams): string
    {
        return '?' . \http_build_query($queryParams);
    }

    /**
     * @return array<string|int, mixed>
     */
    protected static function getJsonContent(KernelBrowser $client, bool $assoc = true): array
    {
        return \json_decode($client->getResponse()->getContent(), $assoc, 512, JSON_THROW_ON_ERROR);
    }
}
