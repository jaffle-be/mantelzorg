<?php namespace Data;

// Composer: "fzaninotto/faker": "v1.3.0"
use App\Mantelzorger\Oudere;
use App\Meta\Context;
use Illuminate\Database\Seeder;

class Ouderen extends Seeder
{

    protected $mantelzorgerRelation;

    protected $woonSituatie;

    protected $oorzaakHulpbehoefte;

    protected $belProfiel;

    public function run()
    {
        $this->relations();

        $faker = Faker::create();

        $mantelzorgers = Mantelzorger::all();

        foreach ($mantelzorgers as $mantelzorger) {
            $this->addData($faker, $mantelzorger);
        }
    }

    public function addData($faker, $mantelzorger)
    {
        foreach (range(1, 5) as $index) {
            Oudere::create([
                'identifier'               => $faker->uuid,
                'email'                    => $faker->unique()->email,
                'firstname'                => $faker->firstName,
                'lastname'                 => $faker->lastName,
                'male'                     => $faker->boolean(),
                'street'                   => $faker->address,
                'postal'                   => $faker->postcode,
                'city'                     => $faker->city,
                'phone'                    => $faker->phoneNumber,
                'mantelzorger_id'          => $mantelzorger->id,
                'mantelzorger_relation_id' => $this->random($this->mantelzorgerRelation->random()->id),
                'birthday'                 => $faker->date(),
                'woonsituatie_id'          => $this->random($this->woonSituatie->random()->id),
                'oorzaak_hulpbehoefte_id'  => $this->random($this->oorzaakHulpbehoefte->random()->id),
                'bel_profiel_id'           => $this->random($this->belProfiel->random()->id)
            ]);
        }
    }

    protected function relations()
    {
        $this->belProfiel = Context::where('context', Context::BEL_PROFIEL)->first()->values;
        $this->woonSituatie = Context::where('context', Context::OUDEREN_WOONSITUATIE)->first()->values;
        $this->oorzaakHulpbehoefte = Context::where('context', Context::OORZAAK_HULPBEHOEFTE)->first()->values;
        $this->mantelzorgerRelation = Context::where('context', Context::MANTELZORGER_RELATION)->first()->values;
    }

    protected function random($id)
    {
        if(rand(0,10) == 0)
        {
            return null;
        }

        return $id;
    }

}