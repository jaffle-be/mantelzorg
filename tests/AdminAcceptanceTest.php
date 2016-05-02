<?php namespace Test;

use App\User;
use Hash;


abstract class AdminAcceptanceTest extends AcceptanceTest
{

    /**
     * @before
     * @priority 1
     */
    public function login()
    {
        $this->user = factory(User::class, 'admin')->create([
            'email' => 'thomas@digiredo.be',
            'password' => Hash::make('password')
        ]);

        $this->visit(url('login'))
            ->submitForm('Aanmelden', ['email' => $this->user->email, 'password' => 'password']);
    }


}
