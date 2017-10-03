<?php

namespace Brunty\Kahlan\Mink;

use Behat\Mink\Element\DocumentElement;
use Behat\Mink\Element\Element;
use Behat\Mink\Exception\ElementNotFoundException;
use Behat\Mink\Session;
use Kahlan\Code\Code;
use Kahlan\Code\TimeoutException;
use Kahlan\Suite;

use function Kahlan\box;

/**
 * Get the Mink session in our current suite
 *
 * @param string $sessionName
 *
 * @return Session
 */
function browser(string $sessionName = 'default'): Session
{
    return Suite::current()->mink->getSession($sessionName);
}

/**
 * Get the page we're currently accessing
 *
 * @param string $sessionName
 *
 * @return DocumentElement
 */
function page(string $sessionName = 'default'): DocumentElement
{
    return browser($sessionName)->getPage();
}

/**
 * Find an element on the page we're browsing
 *
 * @param string       $locator
 * @param Element|null $parent
 *
 * @throws ElementNotFoundException
 *
 * @return Element
 */
function element(string $locator = 'body', Element $parent = null): Element
{
    $parent = $parent ?: page();
    $element = $parent->find('css', $locator);

    if ( ! $element) {
        throw new ElementNotFoundException(browser()->getDriver(), null, 'css', $locator);
    }

    return $element;
}

/**
 * Returns a URL relative to the base URL registered in the Kahlan box
 *
 * @param string $url
 * @param string $boxKey
 *
 * @return string
 */
function url(string $url, string $boxKey = 'brunty.kahlan-mink.base-url'): string
{
    return rtrim(box($boxKey), '/') . '/' . ltrim($url, '/');
}

/**
 * @param      $content
 * @param bool $asArray
 *
 * @return mixed|\stdClass|array|string
 */
function json($content, bool $asArray = true)
{
    if (is_array($content) || $content instanceof \JsonSerializable) {
        return \json_encode($content);
    }

    return \json_decode($content, $asArray);
}

/**
 * Start a PHP Web server and register the PID it's running under in Kahlan's container
 *
 * @param string $host
 * @param int    $port
 * @param string $public
 */
function startServer(string $host = 'localhost', int $port = 8888, string $public = './public'): void
{
    $output = [];
    exec("php -S {$host}:{$port} -t {$public} >/dev/null 2>&1 & echo \$!", $output);

    try {
        $socket = Code::spin(
            function () use ($host, $port) {
                return @fsockopen($host, $port);
            },
            $timeout = 10,
            $delay = 100
        );
        fclose($socket);
    } catch (TimeoutException $e) {
        echo $e->getTraceAsString();
        echo "Unable to start the web server.\n";
        exit(-1);
    }

    box('brunty.kahlan-mink.server.host', $host);
    box('brunty.kahlan-mink.server.port', $port);
    box('brunty.kahlan-mink.server.pid', (int) $output[0]);
}

/**
 * Stop the PHP Web server running via the PID that's stored in Kahlan's container
 */
function stopServer(): void
{
    exec('kill ' . box('brunty.kahlan-mink.server.pid'));
}
