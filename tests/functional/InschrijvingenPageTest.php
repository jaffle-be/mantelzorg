<?php namespace Test\Functional;

use App\Beta\Registration;
use Laracasts\TestDummy\Factory;
use Test\AdminFunctionalTest;

class InschrijvingenPageTest extends AdminFunctionalTest
{

    public function test_seeing_all_inschrijvingen()
    {
        $this->login();

        factory(Registration::class)->create();

        $this->assertSame(4, Registration::count());

        $this->visit(route('inschrijvingen.index'));

        $this->see('row1')
            ->see('row2')
            ->see('row3')
            ->see('row4');
    }

    public function test_searching()
    {
        $this->login();

        factory(Registration::class)->create();
        factory(Registration::class)->create(['firstname' => 'thomas', 'lastname' => 'warlop']);

        $this->visit(route('inschrijvingen.index'))
            ->submitForm('Zoeken', ['query' => 'thomas']);

        $this->seePageIs(route('inschrijvingen.index', ['query' => 'thomas']))
            ->see('thomas warlop')
            ->see('row1')
            ->see('row2', true);
    }

}
