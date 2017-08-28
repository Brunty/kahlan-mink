<?php declare(strict_types=1);

namespace Brunty\Kahlan\Mink;

use Kahlan\Filter\Chain;
use Kahlan\Filter\Filter;
use Kahlan\Cli\Kahlan;

class PhpServer
{
    /**
     * Registers a PHP Server with Kahlan to start when the suite runs, and stop when Kahlan stops.
     *
     * @param Kahlan $kahlan
     * @param string $host
     * @param int $port
     * @param string $public
     *
     * @throws \Exception
     */
    public static function register(
        Kahlan $kahlan,
        string $host = 'localhost',
        int $port = 8888,
        string $public = './public'
    ): void {
        Filter::register('server.start', function (Chain $chain) use ($host, $port, $public) {
            startServer($host, $port, $public);

            return $chain->next();
        });

        Filter::register('server.stop', function (Chain $chain) {
            stopServer();

            return $chain->next();
        });

        Filter::apply($kahlan, 'run', 'server.start');
        Filter::apply($kahlan, 'stop', 'server.stop');
    }
}
