<?php namespace Test\Functional;

use App\Mantelzorger\Oudere;
use App\Meta\Context;
use App\Meta\Value;
use Laracasts\TestDummy\Factory;
use Test\FunctionalTest;

class OuderenPageTest extends FunctionalTest
{

    public function test_required_fields_creating_oudere()
    {
        $user = $this->login();

        $mantelzorger = Factory::create('mantelzorger', ['hulpverlener_id' => $user->id]);

        $this->visit(route('instellingen.{mantelzorger}.oudere.create', [$mantelzorger]));

        $this->submitForm('Gegevens bewaren', [])
            ->see('error-identifier')
            ->see('error-male')
            ->see('error-woonsituatie')
            ->see('error-oorzaak-hulpbehoefte');
    }

    public function test_creating_oudere()
    {
        $user = $this->login();

        $mantelzorger = $this->create($user);

        $payload = Factory::attributesFor('oudere');

        $birthday = $payload['birthday'];

        $payload = $this->inputPayload($birthday, $payload);

        $this->successfullSubmit($payload, $user);

        $payload['birthday'] = $birthday->format('Y-m-d');

        $this->seeInDatabase('ouderen', array_merge(['mantelzorger_id' => $mantelzorger->id], $payload));
    }

    public function test_creating_with_custom_specified_mantelzorger_relation()
    {
        $user = $this->login();

        //go to page
        $mantelzorger = $this->create($user);


        $payload = Factory::attributesFor('oudere');
        //save original birtday date object
        $birthday = $payload['birthday'];
        //manipulate array for form input
        $payload = $this->inputPayload($birthday, $payload);

        //change relation to be a custom value
        $payload['mantelzorger_relation_id'] = '*new*';
        $payload['mantelzorger_relation_id_alternate'] = 'Some new value';

        //submit the form
        $this->successfullSubmit($payload, $user);

        //see the new value
        $context = Context::where('context', Context::MANTELZORGER_RELATION)->first();
        $this->seeInDatabase('meta_values', ['value' => 'Some new value', 'context_id' => $context->id]);

        $newValue = Value::where('value', 'Some new value')->where('context_id', $context->id)->first();

        $payload['birthday'] = $birthday->format('Y-m-d');

        $payload['mantelzorger_relation_id'] = $newValue->id;
        unset($payload['mantelzorger_relation_id_alternate']);

        //see the new value attached to the newly created record
        $this->seeInDatabase('ouderen', array_merge(['mantelzorger_id' => $mantelzorger->id], $payload));
    }

    public function test_required_fields_editing()
    {
        $user = $this->login();

        $oudere = $this->edit($user);

        $this->submitForm('Gegevens bewaren', [
            'identifier' => '',
            'woonsituatie_id' => '',
            'oorzaak_hulpbehoefte_id' => '',
        ])
            ->see('error-identifier')
            ->see('error-woonsituatie-id')
            ->see('error-oorzaak-hulpbehoefte-id');
    }

    public function test_editing()
    {
        $user = $this->login();

        $oudere = $this->edit($user);

        $payload = Factory::attributesFor('oudere');

        $birthday = $payload['birthday'];
        $payload = $this->inputPayload($birthday, $payload);

        $this->submitForm('Gegevens bewaren', $payload)
            ->seePageIs(route('instellingen.{hulpverlener}.mantelzorgers.index', [$user]));

        $payload['birthday'] = $birthday->format('Y-m-d');
        $payload['id'] = $oudere->id;

        //the record with the id should contain the new data.
        $this->seeInDatabase('ouderen', $payload);
    }

    /**
     * @param $user
     *
     * @return mixed
     */
    protected function create($user)
    {
        $mantelzorger = Factory::create('mantelzorger', ['hulpverlener_id' => $user->id]);

        $this->visit(route("instellingen.{mantelzorger}.oudere.create", [$mantelzorger]));

        return $mantelzorger;
    }

    /**
     * @param $user
     *
     * @return mixed
     */
    protected function edit($user)
    {
        $mantelzorger = Factory::create('mantelzorger', ['hulpverlener_id' => $user->id]);

        $oudere = Factory::create('oudere', ['mantelzorger_id' => $mantelzorger->id]);

        $this->visit(route("instellingen.{mantelzorger}.oudere.edit", [$mantelzorger, $oudere]));

        return $oudere;
    }

    /**
     * @param $birthday
     * @param $payload
     *
     * @return array
     */
    protected function inputPayload($birthday, $payload)
    {
        $payload['birthday'] = $birthday->format('d/m/Y');

        $payload = array_only($payload, [
            'identifier', 'firstname', 'lastname', 'birthday', 'male', 'street', 'postal', 'city', 'email', 'phone',
            'woonsituatie_id', 'oorzaak_hulpbehoefte_id', 'bel_profiel_id', 'details_diagnose'
        ]);

        return $payload;
    }

    /**
     * @param $payload
     * @param $user
     */
    protected function successfullSubmit($payload, $user)
    {
        $this->submitForm('Gegevens bewaren', $payload)
            ->seePageIs(route('instellingen.{hulpverlener}.mantelzorgers.index', [$user]));
    }

}