<?php namespace Test\Acceptance;

use App\Beta\Registration;
use Laracasts\TestDummy\Factory;
use Test\AdminAcceptanceTest;

class InschrijvingenPageTest extends AdminAcceptanceTest
{

    /**
     * @setUp
     * @priority 5
     */
    protected function addInschrijvingen()
    {
        Factory::times(20)->create(Registration::class);
    }

    /**
     * @tearDown
     * @priority 5
     */
    protected function removeInschrijvingen()
    {
        app('log')->notice('removing');
        Registration::whereNotNull('id')->delete();
    }

    public function test_deleting_some_inschrijvingen()
    {
        app('log')->notice('testing');
        $this->open(route('inschrijvingen.index'));
        $this->snap();

        $this->check('row1')
            ->check('row2')
            ->check('row3')
            ->click('actions')
            ->click('remove')
            ->click('confirm')
            ->seePageIs(route('inschrijvingen.index'))
            ->see('row17')
            ->notSee('row18')
            ->notSee('row19')
            ->notSee('row20');
    }

//    public function test_deleting_all_registrations()
//    {
//        $this->open(route('inschrijvingen.index'))
//            ->click('actions')
//            ->click('select-all')
//            ->click('actions')
//            ->click('remove')
//            ->click('confirm')
//            ->seePageIs(route('inschrijvingen.index'))
//            ->notSee('row1');
//    }

}