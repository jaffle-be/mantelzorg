<?php namespace Integrated;

use Exception;
use Illuminate\Foundation\Testing\Concerns\InteractsWithDatabase;
use Symfony\Component\DomCrawler\Crawler;
use WebDriver\Element;
use WebDriver\Exception\InvalidSelector;
use WebDriver\Exception\NoSuchElement;
use PHPUnit_Framework_ExpectationFailedException as PHPUnitException;
use WebDriver\Exception\CurlExec;
use InvalidArgumentException;
use WebDriver\WebDriver;
use WebDriver\Session;

class Selenium extends \PHPUnit_Framework_TestCase
{
    use IntegrationTrait, InteractsWithDatabase;

    /**
     * The Illuminate application instance.
     *
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * The WebDriver instance.
     *
     * @var WebDriver
     */
    protected $webDriver;

    /**
     * The current session instance.
     *
     * @var Session
     */
    protected $session;

    /**
     * Setup the test environment.
     *
     * @setUp
     * @return void
     */
    public function setUpLaravel()
    {
        if (! $this->app) {
            $this->refreshApplication();
        }
    }

    /**
     * Clean up the testing environment before the next test.
     *
     * @tearDown
     * @return void
     */
    public function tearDownLaravel()
    {
        if ($this->app) {
            $this->app->flush();
        }
    }

    /**
     * Refresh the application instance.
     *
     * @return void
     */
    protected function refreshApplication()
    {
        putenv('APP_ENV=testing');

        $this->app = $this->createApplication();
    }

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    protected function createApplication()
    {
        $app = require ('bootstrap/app.php');

        $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

        return $app;
    }

    /**
     * Get the base url for all requests.
     *
     * @return string
     */
    protected function baseUrl()
    {
        if (isset($this->baseUrl)) {
            return $this->baseUrl;
        }

        $config = $this->getPackageConfig();

        if (isset($config['baseUrl'])) {
            return $config['baseUrl'];
        }

        return 'http://localhost:8888';
    }

    /**
     * Call a URI in the application.
     *
     * @param  string $requestType
     * @param  string $uri
     * @param  array  $parameters
     * @return self
     */
    protected function makeRequest($requestType, $uri, $parameters = [])
    {
        try {
            $this->closeBrowser();
            $this->session = $this->newSession()->open($uri);
            $this->updateCurrentUrl();
        } catch (CurlExec $e) {
            throw new CurlExec(
                "Hold on there, partner. Did you maybe forget to boot up Selenium? " .
                "\n\njava -jar selenium-server-standalone-*.jar" .
                "\n\n" . $e->getMessage()
            );
        }

        return $this;
    }

    /**
     * Click a link with the given body.
     *
     * @param  string $name
     * @return static
     */
    public function click($name)
    {
        $page = $this->currentPage();

        try {
            $link = $this->findByBody($name)->click();
        } catch (InvalidArgumentException $e) {
            $link = $this->findByNameOrId($name)->click();
        }

        $this->updateCurrentUrl();

        $this->assertPageLoaded(
            $page,
            "Successfully clicked on a link with a body, name, or class of '{$name}', " .
            "but its destination, {$page}, did not produce a 200 status code."
        );

        return $this;
    }

    /**
     * Find an element by its text content.
     *
     * @param  string $body
     * @return Element
     */
    protected function findByBody($body)
    {
        try {
            return $this->session->element('link text', $body);
        } catch (NoSuchElement $e) {
            throw new InvalidArgumentException('No element with the given body exists.');
        }
    }

    /**
     * Filter according to an element's name or id attribute.
     *
     * @param  string $name
     * @param  string $element
     * @return Crawler
     */
    protected function findByNameOrId($name, $element = '*')
    {
        $name = str_replace('#', '', $name);

        try {
            return $this->session->element('css selector', "#{$name}, *[name={$name}]");
        } catch (NoSuchElement $e) {
            throw new InvalidArgumentException(
                "Couldn't find an element, '{$element}', with a name or class attribute of '{$name}'."
            );
        }
    }

    /**
     * Find an element by its "value" attribute.
     *
     * @param  string $value
     * @param  string $element
     * @return \Session
     */
    protected function findByValue($value, $element = 'input')
    {
        try {
            return $this->session->element('css selector', "{$element}[value='{$value}']");
        } catch (NoSuchElement $e) {
            try {
                return $this->session->element('xpath', "//button[contains(text(),'{$value}')]");
            } catch (NoSuchElement $e) {
                throw new InvalidArgumentException(
                    "Crap. Couldn't find an {$element} with a 'value' attribute of '{$value}'. We also looked " .
                    "for a button that contains the text, '{$value}', but no dice either."
                );
            }
        }
    }

    /**
     * Submit a form on the page.
     *
     * @param  string $buttonText
     * @param  array $formData
     * @return static
     */
    public function submitForm($buttonText, $formData = [])
    {
        foreach ($formData as $name => $value) {
            // Weird, but that's what you gotta do. :)
            $value = ['value' => [$value]];

            $element = $this->findByNameOrId($name);
            $tag = $element->name();

            if ($tag == 'input' && $element->attribute('type') == 'checkbox') {
                $element->click();
            } else {
                $element->postValue($value);
            }
        }

        $this->findByValue($buttonText)->submit();

        $this->updateCurrentUrl();

        return $this;
    }

    /**
     * Fill in an input with the given text.
     *
     * @param  string $text
     * @param  string $element
     * @return static
     */
    public function type($text, $element)
    {
        $value = ['value' => [$text]];
        $this->findByNameOrId($element, $text)->postValue($value);

        return $this;
    }

    /**
     * Check a checkbox.
     *
     * @param  string $element
     * @return static
     */
    public function check($element)
    {
        $this->findByNameOrId($element)->click();

        return $this;
    }

    /**
     * Alias that defers to check method.
     *
     * @param  string $element
     * @return static
     */
    public function tick($element)
    {
        return $this->check($element);
    }

    /**
     * Attach a file to a form.
     *
     * @param  string $element
     * @param  string $absolutePath
     * @return static
     */
    public function attachFile($element, $absolutePath)
    {
        $path = ['value' => [$absolutePath]];

        $this->findByNameOrId($element)->postValue($path);

        return $this;
    }

    /**
     * Press the form submit button with the given text.
     *
     * @param  string $buttonText
     * @return static
     */
    public function press($buttonText)
    {
        return $this->submitForm($buttonText);
    }

    /**
     * Assert that an alert box is displayed, and contains the given text.
     *
     * @param  string  $text
     * @param  boolean $accept
     * @return
     */
    public function seeInAlert($text, $accept = true)
    {
        try {
            $alert = $this->session->alert_text();
        } catch (\WebDriver\Exception\NoAlertOpenError $e) {
            throw new PHPUnitException(
                "Could not see '{$text}' because no alert box was shown."
            );
        } catch (\WebDriver\Exception\UnknownError $e) {
            // This would only apply to the PhantomJS driver.
            // It seems to have issues with alerts, so I'm
            // not sure what we can do about that...
            return $this;
        }

        $this->assertContains($text, $alert);

        if ($accept) {
            $this->acceptAlert();
        }

        return $this;
    }

    /**
     * Accept an alert.
     *
     * @return static
     */
    public function acceptAlert()
    {
        try {
            $this->session->accept_alert();
        } catch (\WebDriver\Exception\NoAlertOpenError $e) {
            throw new PHPUnitException(
                "Well, tried to accept the alert, but there wasn't one. Dangit."
            );
        }

        return $this;
    }

    /**
     * Take a snapshot of the current page.
     *
     * @param  string|null $filename
     * @return static
     */
    public function snap($filename = 'screenshot.png')
    {
        $destination = storage_path(sprintf('tests/screens/%s', $filename));

        $this->put(
            $destination,
            base64_decode($this->session->screenshot())
        );

        return $this;
    }

    /**
     * Make a directory tree recursively.
     *
     * @param  string $dir
     * @return void
     */
    public function makeDirectory($dir)
    {
        if (! is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
    }

    /**
     * Put to a file path.
     *
     * @param  string $path
     * @param  string $contents
     * @return mixed
     */
    public function put($path, $contents)
    {
        $this->makeDirectory(dirname($path));

        return file_put_contents($path, $contents);
    }

    protected function sleep($ms = 100)
    {
        usleep($ms * 1000);
    }

    /**
     * Get the content from the last response.
     *
     * @return string
     */
    protected function response()
    {
        return $this->session->source();
    }

    /**
     * Get the status code from the last response.
     *
     * @return integer
     */
    protected function statusCode()
    {
        $response = $this->response();

        // Todo: Temporary. What is the correct way to get the status code?

        if (stristr($response, 'Sorry, the page you are looking for could not be found.')) {
            return 500;
        }

        return 200;
    }

    /**
     * Assert that the filtered Crawler contains nodes.
     *
     * @param  string $filter
     * @throws InvalidArgumentException
     * @return void
     */
    protected function assertFilterProducedResults($filter)
    {
        $element = $this->findByNameOrId($filter);
    }

    /**
     * Close the browser, once the test completes.
     *
     * @tearDown
     * @return void
     */
    public function closeBrowser()
    {
        if ($this->session) {
            $this->session->close();
        }
    }

    /**
     * Halt the process for any number of seconds.
     *
     * @param  integer $seconds
     * @return static
     */
    public function wait($milliseconds = 4000)
    {
        sleep($milliseconds / 1000);

        return $this;
    }

    /**
     * Continuously poll the page, until you find an element
     * with the given name or id.
     *
     * @param  string  $element
     * @param  integer $timeout
     * @return static
     */
    public function waitForElement($element, $timeout = 5000)
    {
        $this->session->timeouts()->postImplicit_wait(['ms' => $timeout]);

        try {
            $this->findByNameOrId($element);
        } catch (InvalidArgumentException $e) {
            throw new InvalidArgumentException(
                "Hey, what's happening... Look, I waited {$timeout} milliseconds to see an element with " .
                "a name or id of '{$element}', but no luck. \nIf you could take a look, that'd be greaaattt..."
            );
        }

        return $this;
    }

    /**
     * Create a new WebDriver session.
     *
     * @param  string $browser
     * @return Session
     */
    protected function newSession()
    {
        $config = $this->getPackageConfig();

        $host = isset($config['selenium']['host']) ? $config['selenium']['host'] : 'http://localhost:4444/wd/hub';

        $this->webDriver = new WebDriver($host);
        $capabilities = [];

        return $this->session = $this->webDriver->session($this->getBrowserName(), $capabilities);
    }

    /**
     * Retrieve the user's desired browser for the tests.
     *
     * @return string
     */
    protected function getBrowserName()
    {
        $config = $this->getPackageConfig();

        if (isset($config['selenium'])) {
            return $config['selenium']['browser'];
        }

        return 'firefox';
    }

    protected function dragAndDrop(Element $src, Element $target)
    {
        $this->session->moveto(['element' => $src->getID()]);
        $this->session->buttondown();
        $this->session->moveto(['element' => $target->getID()]);
        $this->session->buttonup();
    }

    protected function open($uri)
    {
        if (!$this->session) {
            throw new Exception('Need to call visit before using the open method. as we need an open browser');
        }

        $this->session->open($uri);

        $this->updateCurrentUrl();

        return $this;
    }

    protected function updateCurrentUrl()
    {
        $this->currentPage = $this->session->url();

        $this->crawler = new Crawler($this->response(), $this->currentPage());

        return $this;
    }

    public function select($select, $option)
    {
        if ($element = $this->session->element('xpath', "//select[@name='$select']/option[@value='$option']")) {
            $element->click();
        }

        return $this;
    }

    public function submitFormWrapped($wrapper, $submitter, $data = [])
    {
        $submitter = $this->findWrapped($wrapper, $submitter);

        if ($submitter) {
            foreach ($data as $name => $value) {
                $element = $this->findWrapped($wrapper, $name);

                if ($element->name() == 'input' && ($element->attribute('type') == 'checkbox' || $element->attribute('type') == 'radio')) {
                    $element->click();
                } else {
                    $element->postValue($this->post($value));
                }
            }
        }

        $submitter->click();

        $this->updateCurrentUrl();

        return $this;
    }

    public function fillWrapped($wrapper, $name, $value)
    {
        return $this->typeWrapped($wrapper, $name, $value);
    }

    public function typeWrapped($wrapper, $name, $value)
    {
        $element = $this->findWrapped($wrapper, $name);

        $element->clear();

        $element->postValue($this->post($value));

        return $this;
    }

    /**
     * @param $wrapper
     * @param $name
     *
     * @return \WebDriver\Element
     * @throws \Exception
     */
    protected function findWrapped($wrapper, $name)
    {
        $class = '.' . $name;
        $id = '#' . $name;

        //first do a find as if the wrapper is an actual css selector
        //we also allow the name to be an actual selector, this is the only place where we allow this.
        //since the other finders are more meant for forms, we should most likely be able to find
        //every input element using those methods instead of using a specific selector.
        //its much easier to just type 'something' and it autolooks for '#something' or '.something' or '[name=something]'
        //within the provided wrapper
        //you should always be able to identify a field by one of those 3 methods.


        //first try finding elements using specific selectors
        try {

            try{
                //find using both being specific
                $selector = "$wrapper $name";

                return $this->session->element('css selector', $selector);
            }
            catch(NoSuchElement $e)
            {
                try{
                    //find using specific $wrapper
                    $selector = "$wrapper $class, $wrapper $id, $wrapper [name='$name']";

                    return $this->session->element('css selector', $selector);
                }
                catch(NoSuchElement $e)
                {
                    //find using specific $name
                    $selector = "#$wrapper $name, .$wrapper $name";

                    return $this->session->element('css selector', $selector);
                }
            }

        }
        catch (NoSuchElement $e) {
            //next we run through finders more specific to forms
            try {
                $selector = "#$wrapper $id, #$wrapper $class, #$wrapper [name='$name']";

                return $this->session->element('css selector', $selector);
            }
            catch (InvalidSelector $e)
            {
                //if this one fails due to invalid selector, we assume we tried selecting by a specific selector,
                //therefor you end up with stuff like #[name='somename'] which is invalid
                //if this happens, we throw a manual exception
                throw new \Exception("we couldn't find an element using a specific selector: $selector");
            }
            catch (NoSuchElement $e) {
                //now try to find it with a wrapper class
                $selector = ".$wrapper $id, .$wrapper $class, .$wrapper [name='$name']";

                return $this->session->element('css selector', $selector);
            }
        }
    }

    public function find($name)
    {
        $class = '.' . $name;
        $id = '#' . $name;

        try {
            return $this->session->element('css selector', $name);
        }
        catch (NoSuchElement $e) {
            $selector = "$id, $class, [name='$name']";

            return $this->session->element('css selector', $selector);
        }
    }

    public function findCss($selector)
    {
        return $this->session->element('css selector', $selector);
    }

    protected function post($value)
    {
        return ['value' => [$value]];
    }

    public function isSelected($selector, $option)
    {
        $element = $this->session->element('xpath', "//select[@id='$selector']/option[@value='$option'] | //select[@name='$selector']/option[@value='$option']");

        $this->assertTrue($element->selected(), "the element '$selector' was not selected");

        return $this;
    }

    public function waitForCss($selector, $timeout = 2000, $interval = 50, $checkVisibility = true)
    {
        $steps = ceil($timeout / $interval);
        $step = 0;

        while($step < $steps)
        {
            $step++;

            $this->session->timeouts()->postImplicit_wait(['ms' => $interval]);

            try {
                $element = $this->findCss($selector);

                if($element->displayed())
                {
                    return $element;
                }
                else{
                    throw new NoSuchElement("element was found but invisible");
                }

            } catch (NoSuchElement $e) {
            }
        }
        throw $e;
    }

}
