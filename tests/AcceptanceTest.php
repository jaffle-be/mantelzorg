<?php namespace Test;

use App\Organisation\Organisation;
use App\User;
use Hash;
use Integrated\AnnotationReader;
use Integrated\Selenium;
use Laracasts\Integrated\Extensions\Traits\WorksWithDatabase;
use Laracasts\Integrated\Services\Laravel\Application;
use Laracasts\TestDummy\Factory;

abstract class AcceptanceTest extends Selenium
{

    use Application;
    use WorksWithDatabase;

    protected $baseUrl;

    protected $user;

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
        $this->user = Factory::create('user', [
            'email' => 'thomas@digiredo.be',
            'password' => Hash::make('password')
        ]);

        $this->visit(route('login'))
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

    /**
     * @setUp
     */
    protected function setBaseUrl()
    {
        $this->baseUrl = env('APP_URL');
    }

    public function visit($uri)
    {
        $this->sleep();

        return parent::visit($uri);
    }

    protected function open($uri)
    {
        $this->sleep();

        return parent::open($uri);
    }

    protected function sleep()
    {
        usleep(100000);
    }

}