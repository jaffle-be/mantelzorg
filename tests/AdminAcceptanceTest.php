<?php

use Laracasts\TestDummy\Factory;

abstract class AdminAcceptanceTest extends AcceptanceTest
{

    /**
     * @setUp
     * @priority 1
     */
    public function login()
    {
        $user = Factory::create('admin', [
            'password' => Hash::make('password')
        ]);

        $this->visit(route('login'))
            ->submitForm('Aanmelden', ['email' => $user->email, 'password' => 'password']);
    }


}