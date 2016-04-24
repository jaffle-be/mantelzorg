<?php

require_once __DIR__.'../../../vendor/autoload.php';

class TestLoader extends \PHPUnit_Extensions_Selenium2TestCase
{

    protected $Baseurl = 'mantelzorg50.local';
    protected $env = 'local';


    protected function setUp()
    {
        error_reporting(0);
        if ($this->env == 'local') {
            $this->setBrowser('phantomjs');
            $this->setBrowserUrl($this->Baseurl);
            $this->run_selenium_server();
            $this->run_phantom_js();
        }
        //THIS ELSE IS TO USE TRAVIS AND SOUCE LABS TO RUN THE TEST FOR CONTINOUS INTEGRATION
        //IF YOU DONT USE IT, JUST DELETE IT
        else {
            $this->setPort(80);
            $user = 'YOUR USERNAME';
            $token = 'YOUR TOKEN';
            $this->setHost("$user:$token@ondemand.saucelabs.com");
            $this->setPort(80);
            $this->setBrowser('firefox');
            $this->setBrowserUrl($this->Baseurl);
        }

    }

    protected function run_selenium_server()
    {
        if ($this->selenium_server_already_running()) {
            fwrite(STDOUT, "Selenium server already running\n");
        } else {
            shell_exec("java -jar ".__DIR__."..\bin\selenium-server-standalone-2.52.0.jar");
        }
    }

    protected function run_phantom_js()
    {
        if ($this->phantom_js_already_running()) {
            fwrite(STDOUT, "PhantomJS already running\n");
        } else {
            fwrite(STDOUT, "Starting PhantomJS\n");
            shell_exec(__DIR__."tests\bin\phantomjs --webdriver=8080 --webdriver-selenium-grid-hub=http://127.0.0.1:4444");
        }
    }

    protected function selenium_server_already_running()
    {
        return fsockopen("localhost", 4444);
    }

    protected function phantom_js_already_running()
    {
        try {
            return fsockopen("localhost", 8080);
        } catch (Exception $e) {
        }
    }

    protected function takeScreenShot($location){
        $fp = fopen($location,'wb');
        fwrite($fp,$this->currentScreenshot());
        fclose($fp);
    }

    protected function see($name)
    {
        return $this->byXpath("//*[contains(text(),'".$name."')]");
    }

    protected function click($name)
    {
        $element = $this->byXpath("//*[contains(text(),'".$name."')]");
        $element->click();
    }

    protected function seePageIs($name)
    {
        $this->assertEquals($this->Baseurl.$name, $this->url());
    }

    protected function clickLink($link)
    {
        $element = $this->byXpath("//a[@href='".$link."']");
        $element->click();
    }

    protected function visit($path)
    {
        $this->url($path);
    }
}