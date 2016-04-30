<?php namespace Test;

use Hash;
use Laracasts\TestDummy\Factory;

abstract class AdminAcceptanceTest extends AcceptanceTest
{

    /**
     * @before
     * @priority 1
     */
    public function login()
    {
        $this->user = Factory::create('admin', [
            'email' => 'thomas@digiredo.be',
            'password' => Hash::make('password')
        ]);

        $this->visit(url('login'))
            ->submitForm('Aanmelden', ['email' => $this->user->email, 'password' => 'password']);
    }


}