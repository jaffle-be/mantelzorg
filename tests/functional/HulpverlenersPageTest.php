<?php
namespace Test\Functional;

use App\User;
use Laracasts\TestDummy\Factory;
use Test\AdminFunctionalTest;

class HulpverlenersPageTest extends AdminFunctionalTest
{
    public function test_showing_hulpverleners()
    {
        $this->login();

        //create only 9 users, as there is a logged in user, and we only show 10 records,
        //so this is certainly smaller then the rows per page
        $users = factory('user', 9)->create();

        $this->sleep();

        $this->visit(route('hulpverleners.index'));

        foreach(User::all() as $user)
        {
            $this->see(e($user->fullname))
                ->see($user->email)
                ->see($user->organisation->name);
        }
    }

    public function test_page_with_no_users()
    {
        $this->login();
        $this->visit(route('hulpverleners.index'));
    }

    public function test_searching()
    {
        $this->login();

        factory('user')->create([
            'firstname' => 'thomas 1'
        ]);
        factory('user')->create([
            'firstname' => 'thomas 2'
        ]);

        factory('user')->create([
            'firstname' => 'rudy 1'
        ]);
        factory('user')->create([
            'firstname' => 'rudy 2'
        ]);

        $this->visit(route('hulpverleners.index'));

        //search for each name and check the count
        //this should do, some occasions might have data collisions,
        //if some field contains rudy or thomas, but that shouldn't be very likely.
        $this->submitForm('Zoeken', ['query' => 'thomas'])
            ->seePageIs(route('hulpverleners.index', ['query' => 'thomas']))
            ->see('Zoeken')
            ->see('pager', true)
            ->see('pagination', true)
            ->see('thomas 1')
            ->see('thomas 2');

        $this->submitForm('Zoeken', ['query' => 'rudy'])
            ->seePageIs(route('hulpverleners.index', ['query' => 'rudy']))
            ->see('Zoeken')
            ->see('pager', true)
            ->see('pagination', true)
            ->see('rudy 1')
            ->see('rudy 2');
    }

    public function testing_pagination()
    {
        $this->login();

        Factory::times(20)->create('user');

        $this->visit(route('hulpverleners.index'))
            ->see('pager')
            ->see('pagination');

        $this->click(2)
            ->seePageIs(route('hulpverleners.index', ['page' => 2]));

        factory('user', 20)->create(['firstname' => 'thomas']);

        $this->visit(route('hulpverleners.index'));

        $this->submitForm('Zoeken', ['query' => 'thomas'])
            ->seePageIs(route('hulpverleners.index', ['query' => 'thomas']))
            ->click(2)
            ->seePageIs(route('hulpverleners.index', ['page' => 2, 'query' => 'thomas']));
    }

}
