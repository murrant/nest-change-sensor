<?php namespace Tests;

use Illuminate\Support\Str;
use Symfony\Component\Process\Process;

trait SupportsPhantomJS
{
    /**
     * The phantomjsdriver process instance.
     * @var $phantomjsProcess \Symfony\Component\Process\Process
     */
    protected static $phantomjsProcess;

    /**
     * Start the phantomjsdriver process.
     *
     * @return void
     */
    public static function startphantomjsDriver($location, $port)
    {
        static::$phantomjsProcess = static::buildphantomjsProcess($location, $port);
        static::$phantomjsProcess->start();
        static::$phantomjsProcess->waitUntil(function ($type, $output) {
            return Str::contains($output, 'GhostDriver - Main - running');
        });

        static::afterClass(function () {
            static::stopphantomjsDriver();
        });
    }

    /**
     * Build the process to run the phantomjsdriver.
     *
     * @return \Symfony\Component\Process\Process
     */
    protected static function buildphantomjsProcess($location, $port)
    {
        return new Process(
            [$location, '--webdriver=' . $port],
            null,
            static::phantomjsEnvironment()
        );
    }

    /**
     * Get the phantomjsdriver environment variables.
     *
     * @return array
     */
    protected static function phantomjsEnvironment()
    {
        if (PHP_OS === 'Darwin' || PHP_OS === 'WINNT') {
            return [];
        }
        return ['DISPLAY' => ':0'];
    }

    /**
     * Stop the phantomjsdriver process.
     *
     * @return void
     */
    public static function stopphantomjsDriver()
    {
        if (static::$phantomjsProcess) {
            static::$phantomjsProcess->stop();
        }
    }
}
