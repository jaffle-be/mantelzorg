<?php namespace Test\Acceptance;

use App\User;
use Laracasts\TestDummy\Factory;
use Test\AdminAcceptanceTest;

class HulpverlenerPageTest extends AdminAcceptanceTest
{

    /**
     * @setUp
     * @priority 5
     */
    public function baseHulpverleners()
    {
        Factory::times(20)->create('user');
    }

    public function test_deleting_some_hulpverleners()
    {
        $this->open(route('hulpverleners.index'));
        $this->find('[for="row1"]')->click();
        $this->find('[for="row2"]')->click();
        $this->find('[for="row3"]')->click();

        //this sometimes gave an error, when the first 3 records showed, contained our user record.
        //since we sort according to created at, we'll check if we're in the first few rows

        $users = User::orderBy('created_at', 'asc')->limit(3)->get();
        $user = User::where('email', 'thomas@digiredo.be')->first();

        $this->click('actions')
            ->click('remove')
            ->click('confirm')
            ->wait(1000);

        $previousCount = 21;

        if ($users->contains($user->id)) {
            $this->assertSame(User::count(), $previousCount - 2);
        } else {
            $this->assertSame(User::count(), $previousCount - 3);
        }
    }

    public function testDeletingAll()
    {
        //we have 2 pages, so we'll delete both of them entirely
        $this->open(route('hulpverleners.index'))
            ->click('actions')
            ->click('select-all')
            ->click('actions')
            ->click('remove')
            ->click('confirm');

        $this->open(route('hulpverleners.index'))
            ->click('actions')
            ->click('select-all')
            ->click('actions')
            ->click('remove')
            ->click('confirm')
            ->wait();

        //we should keep one user
        $this->assertSame(User::count(), 1);
    }

}