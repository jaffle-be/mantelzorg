<?php namespace Test\Acceptance;

use Test\AcceptanceTest;

class ProfielPageTest extends AcceptanceTest
{

    public function test_changing_password()
    {
        $this->open(route('instellingen.index'));

        $this->fill('password', 'current-password')
            ->fill('thomas', 'password')
            ->fill('thomas', 'password_confirmation');

        $this->submitForm('Gegevens bewaren')
            ->seePageIs(route('instellingen.index'));

        $this->click('log-out')
            ->seePageIs(route('home'))
            ->click('log-in')
            ->submitForm('Aanmelden', [
                'email' => 'thomas@digiredo.be',
                'password' => 'thomas',
            ])
            ->open(route('instellingen.index'))
            ->seePageIs(route('instellingen.index'));
    }
}