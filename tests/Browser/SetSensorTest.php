<?php

namespace Tests\Browser;

use Facebook\WebDriver\Exception\TimeOutException;
use Facebook\WebDriver\Exception\UnexpectedJavascriptException;
use Illuminate\Support\Str;
use Laravel\Dusk\Browser;
use Tests\Browser\Components\NestSensors;
use Tests\Browser\Pages\NestHomePage;
use Tests\DuskTestCase;

class SetSensorTest extends DuskTestCase
{
    private $wait = 30;

    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testSetUpstairsSensor()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new NestHomePage())
                ->waitFor('@nest-login', $this->wait)
                ->assertSee('Sign in')
                ->click('@nest-login')
                ->waitFor('@nest-username', $this->wait)
                ->type('@nest-username', decrypt(env('NEST_USERNAME')))
                ->type('@nest-password', decrypt(env('NEST_PASSWORD')))
                ->click('@nest-login-submit')
                ->waitFor('@nest-thermostat', $this->wait)
                ->click('@nest-thermostat');

            try {
                $browser->within(new NestSensors(), function ($browser) {
                    $browser->selectSensor(env('NEST_SENSOR_NAME'));
                })
                    ->waitFor('@confirm-sensor')
                    ->click('@confirm-sensor');
            } catch (TimeOutException $te) {
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
