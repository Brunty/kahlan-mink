<?php declare(strict_types=1);

namespace Brunty\Kahlan\Mink;

use Behat\Mink\Driver\DriverInterface;
use Behat\Mink\Driver\GoutteDriver;
use Behat\Mink\Mink as BehatMink;
use Behat\Mink\Session;
use Kahlan\Cli\Kahlan;
use Kahlan\Filter\Chain;
use Kahlan\Filter\Filter;
use Kahlan\Suite;

use function Kahlan\box;

class Mink
{
    /**
     * Registers Mink with Kahlan
     *
     * @param Kahlan $kahlan
     * @param DriverInterface|null $driver
     * @param string $sessionName
     *
     * @throws \InvalidArgumentException
     * @throws \Exception
     */
    public static function register(
        Kahlan $kahlan,
        DriverInterface $driver = null,
        string $sessionName = 'default'
    ): void {
        if ($driver === null) {
            $driver = new GoutteDriver;
        }

        $session = new Session($driver);
        $mink = new BehatMink([$sessionName => $session]);
        $mink->getSession($sessionName)->start();

        box('brunty.kahlan-mink.mink', $mink);

        Filter::register('server.globals', function (Chain $chain) use ($kahlan) {
            /** @var Suite $root */
            $root = $kahlan->suite();
            // Set the browser to be available inside specs
            $root->mink = $mink = box('brunty.kahlan-mink.mink');

            $root->afterEach(function () use ($mink) {
                $mink->resetSessions();
            });

            return $chain->next();
        });

        Filter::apply($kahlan, 'run', 'server.globals');
    }
}
