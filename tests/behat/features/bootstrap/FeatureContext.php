<?php

namespace DsimTest\tests\behat;

use Behat\MinkExtension\Context\MinkContext;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends MinkContext
{
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * @Then the :arg1 element should contain current URL
     * @param $arg1
     */
    public function theElementShouldContainCurrentUrl($arg1)
    {
        $currentUrl = preg_replace(
            '/^([^\:\/]+\:\/\/[^\:\/]+)(?:\:\d+)?(.*)$/',
            '\1\2',
            $this->getSession()->getCurrentUrl()
        );

        $elementText = $this->getSession()->getPage()->findById(ltrim($arg1, '#'))->getText();

        if ($currentUrl != $elementText) {
            throw new Exception("Current URL -> {$currentUrl} DOES NOT MATCH {$arg1} element -> {$elementText}");
        }
    }

    /**
     * @Then the radiobutton :arg1 should be checked
     * @param $arg1
     */
    public function theRadiobuttonShouldBeChecked($arg1)
    {
        $radioChecked = $this->getSession()->getPage()->findById(ltrim($arg1, '#'))->getAttribute('checked');

        if ($radioChecked != 'checked') {
            throw new Exception("Radio button {$arg1} IS NOT CHECKED");
        }
    }

    /**
     * @Then the radiobutton :arg1 should not be checked
     * @param $arg1
     */
    public function theRadiobuttonShouldNotBeChecked($arg1)
    {
        $radioChecked = $this->getSession()->getPage()->findById(ltrim($arg1, '#'))->getAttribute('checked');

        if ($radioChecked == 'checked') {
            throw new Exception("Radio button {$arg1} IS CHECKED");
        }
    }
}
