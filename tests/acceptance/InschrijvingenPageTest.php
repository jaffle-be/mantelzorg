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
        factory(Registration::class, 20)->create();
    }

    /**
     * @tearDown
     * @priority 5
     */
    protected function removeInschrijvingen()
    {
        Registration::whereNotNull('id')->delete();
    }

    public function test_deleting_some_inschrijvingen()
    {
        $this->open(route('inschrijvingen.index'));
        $this->snap();

        $this->find('[for="row1"]')->click();
        $this->find('[for="row2"]')->click();
        $this->find('[for="row3"]')->click();
        $this->click('actions')
            ->click('remove')
            ->click('confirm')
            ->seePageIs(route('inschrijvingen.index'))
            ->see('row17')
            ->notSee('row18')
            ->notSee('row19')
            ->notSee('row20');
    }

    public function test_deleting_all_registrations()
    {
        $this->open(route('inschrijvingen.index'))
            ->click('actions')
            ->click('select-all')
            ->click('actions')
            ->click('remove')
            ->click('confirm')
            ->seePageIs(route('inschrijvingen.index'))
            ->notSee('row1');
    }

}
