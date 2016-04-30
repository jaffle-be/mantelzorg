<?php namespace Test;

use Laracasts\TestDummy\Factory;

abstract class AdminFunctionalTest extends FunctionalTest
{
    protected function login(array $payload = [])
    {
        $payload = array_merge($payload, ['password' => 'password']);

        $user = factory('admin')->create($payload);

        $this->be($user);

        return $user;
    }

}
