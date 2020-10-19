# Exceptions

The Unit bundle can throw exception during the test, and most of them are related to a bad test writing. This documentation explains why the exceptions are thrown and how to fix it.


## CsrfTokenManagerMissingException

The CSRF Token manager is missing from the container. It means that it may not be installed at all (`composer require symfony/security-csrf`) or not enabled (in your configuration, check the configuration `framework.csrf_protection: true`). Check the [Symfony's documentation](https://symfony.com/doc/current/security/csrf.html) for more information.


## EntityManagerNotFoundException

The entity manager cannot be found. This means doctrine is not installed or badly configured. Please check the [Symfony's documentation](https://symfony.com/doc/current/doctrine.html).
