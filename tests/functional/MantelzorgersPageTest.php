<?php namespace Test\Functional;

use App\Mantelzorger\Mantelzorger;
use App\Mantelzorger\Oudere;
use App\User;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Collection;
use Test\FunctionalTest;

class MantelzorgersPageTest extends FunctionalTest
{

    public function test_seeing_mantelzorgers()
    {
        $user = $this->login();

        $mantelzorgers = $this->mantelzorgers($user, true);

        $this->assertSame(2, Mantelzorger::count());

        //sleep to avoid elasticsearch delay bug.
        $this->sleep();

        $this->visit(route('instellingen.{hulpverlener}.mantelzorgers.index', [$user]));

        foreach($mantelzorgers as $mantelzorger)
        {
            $this->see($mantelzorger->fullname);

            foreach($mantelzorger->oudere as $oudere)
            {
                $this->see($oudere->fullname);
            }
        }
    }


    public function test_required_fields_creating_mantelzorger()
    {
        $user = $this->login();

        $this->visit(route('instellingen.{hulpverlener}.mantelzorgers.create', [$user]));

        $this->submitForm('Maak nieuwe mantelzorger aan', []);

        $this->see('error-identifier');
        $this->see('error-birthday');
        $this->see('error-male');
    }

    public function test_creating_mantelzorger()
    {
        $user = $this->login();

        $this->visit(route('instellingen.{hulpverlener}.mantelzorgers.create', [$user]));

        $mantelzorger = app(Factory::class)->raw(Mantelzorger::class);

        $birthday = $mantelzorger['birthday'];

        $mantelzorger['birthday'] = $mantelzorger['birthday']->format('d/m/Y');

        $this->submitForm('Maak nieuwe mantelzorger aan', array_except($mantelzorger, ['hulpverlener_id', 'created_at', 'updated_at']));

        $mantelzorger['birthday'] = $birthday->format('Y-m-d') . ' 00:00:00';
        $mantelzorger['hulpverlener_id'] = $user->id;

        unset($mantelzorger['created_at'], $mantelzorger['updated_at']);

        $this->seeInDatabase('mantelzorgers', $mantelzorger);
    }

    public function test_editing_mantelzorger()
    {
        $user = $this->login();

        $mantelzorger = factory(Mantelzorger::class)->create(['hulpverlener_id' => $user->id]);

        $edited = app(Factory::class)->raw(Mantelzorger::class, ['hulpverlener_id']);
        $edited['birthday'] = $edited['birthday']->format('d/m/Y');

        $edited = array_only($edited, ['firstname', 'lastname', 'birthday', 'male', 'street', 'city', 'postal', 'email', 'phone']);

        $this->visit(route('instellingen.{hulpverlener}.mantelzorgers.edit', [$user, $mantelzorger]));

        $this->submitForm('Gegevens bewaren', $edited);

        //we should see the new data with the old id, (which is in just a simple update)
        $edited = array_merge(['id' => $mantelzorger->id], $edited);
        $edited['birthday'] = preg_replace('/(\d{1,2})\/(\d{1,2})\/(\d{4})/', '$3-$2-$1', $edited['birthday']);
        $this->seeInDatabase('mantelzorgers', $edited);
    }

    protected function mantelzorgers(User $user, $ouderen = false)
    {
        $teller = 0;

        $mantelzorgers = new Collection();

        $factory = app(Factory::class);

        while ($teller < 2) {
            $mantelzorger = new Mantelzorger($factory->raw(Mantelzorger::class));

            $user->mantelzorgers()->save($mantelzorger);

            if($ouderen)
            {
                $oudereTeller = 0;

                while($oudereTeller < 3)
                {
                    $oudere = new Oudere($factory->raw(Oudere::class, ['mantelzorger_id' => $mantelzorger->id]));

                    $mantelzorger->oudere()->save($oudere);

                    $oudereTeller++;
                }

            }

            $mantelzorgers->push($mantelzorger);

            $teller++;
        }

        return $mantelzorgers;
    }

}
