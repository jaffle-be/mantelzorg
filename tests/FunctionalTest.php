<?php namespace Test;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase;
use Laracasts\TestDummy\Factory;

abstract class FunctionalTest extends TestCase
{
    use DatabaseTransactions;

    protected $baseUrl = 'http://testing.local:8000';

    /**
     * Do not merge with acceptance reset elasticsearch,
     * they will fire at wrong times and bug our tests.
     * @before
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
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__ . '/../bootstrap/app.php';

        $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

        return $app;
    }

    protected function login(array $payload = [])
    {
        $payload = array_merge($payload, ['password' => 'password']);

        $user = Factory::create('user', $payload);

        $this->be($user);

        return $user;
    }

    public function isSelected($name, $value)
    {
        $selected = $this->crawler->filter("select[name=$name] option[selected=selected]")->attr('value');

        $this->assertEquals($value, $selected, "input $name does not have value $value selected");

        return $this;
    }

    public function isChecked($name, $value)
    {
        $checked = $this->crawler->filter("input[name=$name]:checked")->attr('value');

        $this->assertEquals($value , $checked, "input $name does not have value $value checked");

        return $this;
    }

    public function visit($uri)
    {
        usleep(500);

        return parent::visit($uri);
    }

    protected function open($uri)
    {
        usleep(500);

        return parent::open($uri);
    }

}
