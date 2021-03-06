<?php
namespace Test\Functional;


use App\Questionnaire\Questionnaire;
use Test\FunctionalTest;

class NavigationTest extends FunctionalTest
{

    protected function survey()
    {
        return factory(Questionnaire::class)->create(['active' => true]);
    }

    public function testNavigationWhenSignedIn()
    {
        $this->login();

        $this->survey();

        $this->visit(route('dash'))
            ->click('nav-instrument')
            ->seePageIs(route('dash'));

        $this->click('nav-profiel')
            ->seePageIs(route('instellingen.index'));

        $this->click('nav-mantelzorgers')
            ->seePageIs(route('instellingen.{hulpverlener}.mantelzorgers.index', app('auth')->user()));

        $this->see('nav-hulpverleners', true)
            ->see('nav-inschrijvingen', true)
            ->see('nav-surveys', true)
            ->see('nav-rapport', true)
            ->see('nav-stats', true);
    }

    public function testNavigationWhenSignedInAsAdmin()
    {
        $this->survey();

        $this->login(['admin' => 1]);

        $this->visit(route('dash'));

        $this->click('nav-hulpverleners')
            ->seePageIs(route('hulpverleners.index'));

        $this->click('nav-inschrijvingen')
            ->seePageIs(route('inschrijvingen.index'));

        $this->click('nav-surveys')
            ->seePageIs(route('survey.index'));

        $this->click('nav-rapport')
            ->seePageIs(route('report.index'));

        $user = app('auth')->user();

        $user->id = 1;

        app('auth')->setUser($user);

        $this->visit(route('dash'));

        $this->click('nav-stats')
            ->seePageIs(route('stats.insights.ouderen'));
    }

    public function testClickingLogo()
    {
        $this->login();

        $this->survey();

        $this->visit(route('home'));

        $this->click('brand')
            ->seePageIs(route('dash'));

        app('auth')->logout();

        $this->visit(route('home'));
        $this->click('brand')
            ->seePageIs(route('home'));
    }

    public function testMainNavigation()
    {
        $this->login();

        $this->visit(route('home'))
            ->see('log-out')
            ->see('log-in', true)
            ->see('main-nav-instrument')
            ->click('log-out')
            ->visit(route('dash'))
            ->seePageIs(url('login'))
            ->see('log-out', true)
            ->see('log-in');
    }

}
