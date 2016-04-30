<?php
namespace Test\Functional;

use Laracasts\TestDummy\Factory;
use Test\FunctionalTest;

class LoginPageTest extends FunctionalTest
{

    public function testAccessingAdminWithoutLogin()
    {
        $this->visit(route('hulpverleners.index'))
            ->seePageIs(url('login'))
            ->see('alert-danger');
    }

    public function testLoginActiveUser()
    {
        $credentials = $this->activeUser();

        //when no active survey, the redirect goes to home page
        $this->login($credentials)
            ->seePageIs(route('home'))
            ->see('log-out');
    }

    public function testLoginActiveUserWithActiveSurvey()
    {
        $credentials = $this->activeUser();

        factory('survey')->create(['active' => true]);

        $this->login($credentials)
            ->seePageIs(route('dash'));
    }

    public function testLoginAdminUser()
    {
        $user = factory('admin')->create(['password' => app('hash')->make('thomas')]);

        $credentials = [
            'email'    => $user->email,
            'password' => 'thomas'
        ];
        $this->login($credentials)
            ->seePageIs(route('home'))
            ->see('log-out');
    }

    public function testWrongPassword()
    {
        $user = factory('user')->create();

        $credentials = [
            'email'    => $user->email,
            'password' => 'some password that has to be wrong 99.9999999999999% of the time'
        ];

        $this->login($credentials)
            ->seePageIs(url('login'))
            ->see('alert-danger');
    }

    public function testLoginUnknownUser()
    {
        $this->visit('login')
            ->submitForm('Aanmelden')
            ->seePageIs(url('login'))
            ->see('alert-danger');
    }

    public function testLoginBannedUser()
    {
        $user = factory('banned-user')->create(['password' => app('hash')->make('thomas')]);

        $credentials = [
            'email'    => $user->email,
            'password' => 'thomas',
        ];

        $this->login($credentials)
            ->seePageIs(url('login'))
            ->see('alert-danger');
    }

    protected function login(array $credentials = [])
    {
        return $this->visit(url('login'))
            ->submitForm('Aanmelden', $credentials);
    }

    /**
     * @return array
     */
    protected function activeUser()
    {
        $user = factory('user')->create(['password' => app('hash')->make('thomas')]);

        $credentials = [
            'email'    => $user->email,
            'password' => 'thomas'
        ];

        return $credentials;
    }

}
