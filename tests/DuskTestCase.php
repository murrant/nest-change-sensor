<?php

namespace Tests;

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Laravel\Dusk\TestCase as BaseTestCase;

abstract class DuskTestCase extends BaseTestCase
{
    use CreatesApplication;
    use SupportsPhantomJS;

    /**
     * Prepare for Dusk test execution.
     *
     * @beforeClass
     * @return void
     */
    public static function prepare()
    {
        if (env('DUSK_DRIVER') == 'phantomjs') {
            static::startphantomjsDriver("/usr/bin/phantomjs", 4444);
        } else {
            static::startChromeDriver();
        }
    }

    /**
     * Create the RemoteWebDriver instance.
     *
     * @return \Facebook\WebDriver\Remote\RemoteWebDriver
     */
    protected function driver()
    {
        if (env('DUSK_DRIVER') == 'phantomjs') {
            return RemoteWebDriver::create(
                "http://localhost:4444/wd/hub",
                DesiredCapabilities::phantomjs()
            );
        } else {
            $arguments = [
                '--disable-gpu',
                '--window-size=1920,1080',
            ];

            if (env('CHROME_HEADLESS') !== false) {
                $arguments[] = '--headless';
            }

            $options = (new ChromeOptions)->addArguments($arguments);

            return RemoteWebDriver::create(
                'http://localhost:9515',
                DesiredCapabilities::chrome()->setCapability(ChromeOptions::CAPABILITY, $options)
            );

        }
    }
}
