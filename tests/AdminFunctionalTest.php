<?php

use Laracasts\TestDummy\Factory;

class AdminFunctionalTest extends FunctionalTest
{
    protected function login(array $payload = [])
    {
        $payload = array_merge($payload, ['password' => 'password']);

        $user = Factory::create('admin', $payload);

        $this->be($user);

        return $user;
    }

}