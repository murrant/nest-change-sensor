<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;

class NestHomePage extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return 'https://home.nest.com/home';
    }

    /**
     * Assert that the browser is on the page.
     *
     * @param  Browser  $browser
     * @return void
     */
    public function assert(Browser $browser)
    {
//        $browser->assertPathIs($this->url());
    }

    /**
     * Get the element shortcuts for the page.
     *<a href="/thermostat/09AA01AC421819D3"><div class="puck-icon styles--puckContainer_K7P"><div class="styles--content_2Pp styles--idle_2Tf"><div class="styles--container_1vV styles--mode-heat_3sT"><div class="styles--tempContainer_37J">72</div></div></div></div><div class="puck-label" aria-hidden="true"><h1>Dining Room (Main)</h1><h2 class="puck-status-text" aria-hidden="true"></h2></div></a>
     * @return array
     */
    public function elements()
    {
        return [
            '@nest-login' => '[data-test=nest-login]',
            '@nest-username' => '[data-test=input-email]',
            '@nest-password' => '[data-test=input-password]',
            '@nest-login-submit' => '[data-test=button-login-submit]',
            '@nest-menubar' => 'div[role=menubar]',
            '@nest-thermostat' => env('NEST_THERMOSTAT') ? "a[href='/thermostat/" . env('NEST_THERMOSTAT') . "']" : 'li.puck-item > a',
            '@confirm-sensor' => '[data-test=kryptonite-sensor-schedule-override-alert-confirm-button]',
            '@close-thermostat' => '[data-test=thermozilla-header-back-button]',
            '@account-menu' => 'button[class*=navbar--account-menu__button]',
            '@logout' => 'div[class*=navbar--logout-menu-item]',
            '@logout-confirm' => 'button.buttonGroup-confirm-confirmButton',

//            '@google-login' => '[data-test=google-button-login]',
//            '@login-email' => 'input[type=email]',
//            '@login-id-next' => '[role=button]#identifierNext',
//            '@login-password' => 'input[type=password]',
//            '@login-pw-next' => '[role=button]#passwordNext',
        ];
    }
}
