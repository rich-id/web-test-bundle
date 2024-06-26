The RichCongress Web Test Bundle
=======================================

This version of the bundle requires Symfony 6.0+ and PHP 8.1+.

[![Package version](https://img.shields.io/packagist/v/richcongress/web-test-bundle)](https://packagist.org/packages/richcongress/web-test-bundle)
[![Actions Status](https://github.com/richcongress/web-test-bundle/workflows/Tests/badge.svg)](https://github.com/t/richcongress/web-test-bundle/actions)
[![Coverage Status](https://coveralls.io/repos/github/richcongress/web-test-bundle/badge.svg?branch=master)](https://coveralls.io/github/richcongress/web-test-bundle?branch=master)
[![Maintainability](https://api.codeclimate.com/v1/badges/ac0f4c281486c5e849b6/maintainability)](https://codeclimate.com/github/richcongress/web-test-bundle/maintainability)
[![contributions welcome](https://img.shields.io/badge/contributions-welcome-brightgreen.svg?style=flat)](https://github.com/richcongress/web-test-bundle/issues)
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE.md)

Extends from the Symfony's WebTestCase and brings some improvements such as cleaner tests assertions, easier client creation, and a database generation with its reset between tests.


# Quick start

Quick showcase

# Table of content

1. [Installation](#1-installation)
2. [Getting started](#2-getting-started)
3. [Versioning](#3-versioning)
4. [Contributing](#4-contributing)
5. [Hacking](#5-hacking)
6. [License](#6-license)


# 1. Installation

This version of the bundle requires Symfony 6.0+ and PHP 8.1+.

```bash
composer require richcongress/web-test-bundle
```

# 2 Getting started

- [Exceptions](Docs/Exceptions.md)

# 3. Versioning

web-test-bundle follows [semantic versioning](https://semver.org/). In short the scheme is MAJOR.MINOR.PATCH where
1. MAJOR is bumped when there is a breaking change,
2. MINOR is bumped when a new feature is added in a backward-compatible way,
3. PATCH is bumped when a bug is fixed in a backward-compatible way.

Versions bellow 1.0.0 are considered experimental and breaking changes may occur at any time.


# 4. Contributing

Contributions are welcomed! There are many ways to contribute, and we appreciate all of them. Here are some of the major ones:

* [Bug Reports](https://github.com/richcongress/web-test-bundle/issues): While we strive for quality software, bugs can happen and we can't fix issues we're not aware of. So please report even if you're not sure about it or just want to ask a question. If anything the issue might indicate that the documentation can still be improved!
* [Feature Request](https://github.com/richcongress/web-test-bundle/issues): You have a use case not covered by the current api? Want to suggest a change or add something? We'd be glad to read about it and start a discussion to try to find the best possible solution.
* [Pull Request](https://github.com/richcongress/web-test-bundle/merge_requests): Want to contribute code or documentation? We'd love that! If you need help to get started, GitHub as [documentation](https://help.github.com/articles/about-pull-requests/) on pull requests. We use the ["fork and pull model"](https://help.github.com/articles/about-collaborative-development-models/) were contributors push changes to their personnal fork and then create pull requests to the main repository. Please make your pull requests against the `master` branch.

As a reminder, all contributors are expected to follow our [Code of Conduct](CODE_OF_CONDUCT.md).


# 5. Hacking

You might use Docker and `docker-compose` to hack the project. Check out the following commands.

```bash
# Start the project
docker-compose up -d

# Install dependencies
docker-compose exec application composer install

# Run tests
docker-compose exec application bin/phpunit

# Run a bash within the container
docker-compose exec application bash
```


# 6. License

web-test-bundle is distributed under the terms of the MIT license.

See [LICENSE](LICENSE.md) for details.
