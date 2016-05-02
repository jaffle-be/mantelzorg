<?php namespace Test;



use App\User;

abstract class AdminFunctionalTest extends FunctionalTest
{
    protected function login(array $payload = [])
    {
        $payload = array_merge($payload, ['password' => 'password']);

        $user = factory(User::class, 'admin')->create($payload);

        $this->be($user);

        return $user;
    }

}
