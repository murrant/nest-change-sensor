<?php

namespace Tests\Browser\Components;

use Facebook\WebDriver\Remote\RemoteWebElement;
use Illuminate\Support\Str;
use Laravel\Dusk\Browser;
use Laravel\Dusk\Component as BaseComponent;

class NestSensors extends BaseComponent
{
    private $sensors;

    /**
     * Assert that the browser page contains the component.
     *
     * @param Browser $browser
     * @return void
     */
    public function assert(Browser $browser)
    {
        $browser->waitFor($this->selector(), 30)->assertVisible($this->selector());
        $this->sensors = $this->getSensors($browser);
    }

    /**
     * Get the root selector for the component.
     *
     * @return string
     */
    public function selector()
    {
        return '[data-test=thermozilla-aag-carousel-container]';
    }

    public function getSensors(Browser $browser)
    {
        $sensors = [];
        $elements = $browser->elements("[data-test*='thermozilla-aag-sensors-temperature-sensor']");
        foreach ($elements as $element) {
            /** @var RemoteWebElement $element */
            $id = $element->getAttribute('data-test');
            if (Str::endsWith($id, 'listcell')) {
                $title = $browser->element("[data-test='" . $id . "-title']")->getText();
                $subtitle = $browser->element("[data-test='" . $id . "-subtitle']")->getText();
                $value = $browser->element("[data-test='" . $id . "-value']")->getText();
                $sensors[$id] = compact('id', 'title', 'subtitle', 'value');
            }
        }

        return $sensors;
    }

    /**
     * Get the element shortcuts for the component.
     *
     * @return array
     */
    public function elements()
    {
        return [
        ];
    }

    public function selectSensor(Browser $browser, $name)
    {
        $id = $this->findSensorByName($name);
        $selector = "[data-test='$id-value']";
        // use JS to work around dusk unable to click pseudo elements
        $browser->script(<<<END
document.querySelector("$selector").dispatchEvent(new MouseEvent("click", {
    "view": window,
    "bubbles": true,
    "cancelable": false
}));
END
        );
    }

    public function findSensorByName($name)
    {
        return collect($this->sensors)->first(function ($sensor) use ($name) {
            return Str::contains(strtolower($sensor['title']), strtolower($name));
        })['id'];
    }
}
