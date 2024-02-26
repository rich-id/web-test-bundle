<?php

namespace RichCongress\WebTestBundle\TestCase\TestTrait;

use RichCongress\WebTestBundle\WebTest\Client;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Exception\SessionNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionFactoryInterface;

trait WithSessionTrait
{
    private function withSession(Client $client, callable $callback): mixed
    {
        $container = $client->getBrowser()->getContainer();
        $requestStack = $container->get(RequestStack::class);

        try {
            return $callback($requestStack->getSession());
        } catch (SessionNotFoundException $e) {
            $sessionFactory = $container->get('session.factory');
            $cookies = $client->getBrowser()->getCookieJar();

            $session = $sessionFactory->createSession();
            $cookie = $cookies->get($session->getName());

            if ($cookie !== null) {
                $session->setId($cookie->getValue());
            }

            $session->start();

            $cookie = new Cookie($session->getName(), $session->getId());
            $cookies->set($cookie);

            $request = new Request();
            $request->setSession($session);
            $requestStack->push($request);

            $result = $callback($session);

            $requestStack->pop();
            $session->save();

            return $result;
        }
    }
}
