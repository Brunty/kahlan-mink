# Kahlan Mink

[![Build Status](https://travis-ci.org/Brunty/kahlan-mink.svg?branch=master)](https://travis-ci.org/Brunty/kahlan-mink)

🌍 Provides functionality to work with Mink within Kahlan. Also provides a way to boot a PHP web server when tests run.

Inspired and based on this: http://radify.io/blog/end-to-end-functional-testing-with-kahan/ - many thanks to [Simon JAILLET](https://github.com/jails) for not only Kahlan, but the blog post on end to end testing in Kahlan.

## Requirements

* PHP >= 7.1
* Kahlan ^3.0
* Mink ^1.7
* Mink Goutte Driver ^1.2

## Installation & Usage

`composer require brunty/kahlan-mink --dev`

Once required, setup your configuration file `kahlan-config.php` to register mink with Kahlan (making sure to pass through `$this` to the method):

```php
<?php // kahlan-config.php

\Brunty\Kahlan\Mink\Mink::register($this);
```

If you want to start a PHP server when your tests start and stop it when test execution finishes then register the PhpServer with Kahlan as well:

```php
<?php // kahlan-config.php

\Brunty\Kahlan\Mink\PhpServer::register($this);
```

Once that's done, you can use the functions to access things via Mink:

* `\Brunty\Kahlan\Mink\browser(string $sessionName = 'default'): Session` returns the instance of Mink inside our test suite.
* `\Brunty\Kahlan\Mink\page($sessionName = 'default'): DocumentElement` returns the page we're currently accessing.
* `\Brunty\Kahlan\Mink\element($locator = 'body', Element $parent = null): Element` find an element on the page we're browsing.

```php
<?php // spec/MySiteSpec.php

use function Brunty\Kahlan\Mink\browser;
use function Brunty\Kahlan\Mink\element;

describe('My website', function () {
    it('accesses the site', function () {
        browser()->visit('http://localhost:8888/my-page');
        expect(element('#content p')->getText())->toContain('Hello world!');
    });
});

```

## Contributing

This started as a small personal project.

Although this project is small, openness and inclusivity are taken seriously. To that end a code of conduct (listed in the contributing guide) has been adopted.

[Contributor Guide](CONTRIBUTING.md)
