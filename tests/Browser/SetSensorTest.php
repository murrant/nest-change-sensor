<?php

namespace Tests\Browser;

use Facebook\WebDriver\Exception\TimeOutException;
use Facebook\WebDriver\Exception\UnexpectedJavascriptException;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Str;
use Laravel\Dusk\Browser;
use Tests\Browser\Components\NestSensors;
use Tests\Browser\Pages\NestHomePage;
use Tests\DuskTestCase;

class SetSensorTest extends DuskTestCase
{
    private $wait = 20;

    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testSetUpstairsSensor()
    {
        $this->browse(function (Browser $browser) {
            try {
                $username = decrypt(env('NEST_USERNAME'));
                $password = decrypt(env('NEST_PASSWORD'));
            } catch (DecryptException $decryptException) {
                $this->fail('NEST_USERNAME and NEST_PASSWORD must be set.  Run php artisan nest:credentials');
                return;
            }

            try {
                $browser->visit(new NestHomePage())
                    ->waitFor('@nest-login', $this->wait)
                    ->assertSee('Sign in')
                    ->click('@nest-login')
                    ->waitFor('@nest-username', $this->wait)
                    ->type('@nest-username', $username)
                    ->type('@nest-password', $password)
                    ->click('@nest-login-submit')
                    ->waitFor('@nest-menubar', $this->wait);
            } catch (TimeOutException $toe) {
                $this->fail('Failed to login, check credentials');
            }

            try {
                $browser->waitFor('@nest-thermostat', $this->wait)
                    ->click('@nest-thermostat');
            } catch (TimeOutException $toe) {
                $this->fail('Could not find thermostat, check NEST_THERMOSTAT');
            }

            try {
                $browser->within(new NestSensors(), function ($browser) {
                    $browser->selectSensor(env('NEST_SENSOR_NAME'));
                })
                    ->waitFor('@confirm-sensor')
                    ->click('@confirm-sensor');
            } catch (TimeOutException $toe) {
                // sensor already selected.
            } catch (UnexpectedJavascriptException $uje) {
                if (Str::contains($uje->getMessage(), "Cannot read property 'dispatchEvent' of null")) {
                    $this->fail('Could not find sensor to set, is NEST_SENSOR_NAME set correctly?');
                }
                $this->fail('Failed to set sensor');
            }

            $browser->click('@account-menu')
                ->click('@logout')
                ->click('@logout-confirm')
                ->waitFor('@nest-login', $this->wait)
                ->assertSee('Sign in');
        });
    }
}
