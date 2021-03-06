<?php namespace Test;

use App\Organisation\Organisation;
use App\User;
use Hash;
use Illuminate\Foundation\Testing\Concerns\InteractsWithDatabase;
use Integrated\AnnotationReader;
use Integrated\Selenium;
use WebDriver\WebDriver;

abstract class AcceptanceTest extends Selenium
{

    use InteractsWithDatabase;

    protected $baseUrl;

    protected $user;

    protected $login = true;

    /**
     * We want to use our own AnnotationReader
     * it will allow us to sort different annotation actions
     *
     * @return AnnotationReader
     */
    protected function annotations()
    {
        if (! $this->annotations) {
            $this->annotations = new AnnotationReader($this);
        }

        return $this->annotations;
    }

    /**
     * Do not merge with acceptance reset elasticsearch,
     * they will fire at wrong times and bug our tests.
     * @setUp
     * @priority 1
     */
    protected function resetElasticsearch()
    {
        $search = app('App\Search\SearchServiceInterface');

        $types = config('search.types');

        $types = array_keys($types);

        foreach ($types as $type) {
            $search->flush($type);
        }
    }

    /**
     * @setUp
     * @priority 2
     */
    public function login()
    {
        if(!$this->login)
        {
            $this->visit(route('home'));

            return;
        }

        $this->user = factory(User::class)->create([
            'email' => 'thomas@digiredo.be',
            'password' => Hash::make('password')
        ]);

        $this->visit(url('login'))
            ->submitForm('Aanmelden', ['email' => $this->user->email, 'password' => 'password']);
    }

    /**
     * @tearDown
     * @priority 2
     */
    public function logoutUser()
    {
        User::whereNotNull('id')->delete();
    }

    /**
     * @tearDown
     * @priority 3
     */
    public function cleanDb()
    {
        Organisation::whereNotNull('id')->delete();
    }

    protected function newSession()
    {
        $host = sprintf('http://%s:4444/wd/hub', config('tests.selenium-host', 'localhost'));
        $capabilities = [];

        if(env('TRAVIS') || env('SAUCED'))
        {
            $host = sprintf('http://%s:%s@ondemand.saucelabs.com:80/wd/hub', env('SAUCE_USERNAME'), env('SAUCE_API_KEY'));
        }

        $this->webDriver = new WebDriver($host);

        return $this->session = $this->webDriver->session($this->getBrowserName(), $capabilities);
    }


    /**
     * @setUp
     */
    protected function setBaseUrl()
    {
        if(!env('TRAVIS'))
        {
            $this->baseUrl = env('APP_URL');
        }
        else{
            $this->baseUrl = 'http://localhost';
        }
    }

    public function visit($uri)
    {
        $result = parent::visit($uri);

//        $this->sleep();

        return $result;
    }

    protected function open($uri)
    {
        $result = parent::open($uri);

//        $this->sleep();

        return $result;
    }

}
