<?php
namespace Test\Functional;

use App\Beta\Registration;
use Laracasts\TestDummy\Factory;
use Test\FunctionalTest;

class HomePageTest extends FunctionalTest
{

    public function testRegistrationFeature()
    {
        $payload = [
            'firstname'    => 'thomas',
            'lastname'     => 'warlop',
            'email'        => 'thomas@digiredo.be',
            'organisation' => 'digiredo',
        ];

        $user = Factory::attributesFor(Registration::class, $payload);

        $this->visit(route('home'))
            ->submitForm('Meld je aan', $user)
            ->seeInDatabase('beta_registrations', $user)
            ->seePageIs(route('home'))
            ->see('alert alert-success');
    }

    public function testLoginLink()
    {
        $this->visit(route('home'))
            ->see('log-in')
            ->click('log-in')
            ->seePageIs(url('login'));
    }

}
