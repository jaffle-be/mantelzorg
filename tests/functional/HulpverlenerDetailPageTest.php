<?php namespace Test\Functional;


use App\User;
use Test\AdminFunctionalTest;

class HulpverlenerDetailPageTest extends AdminFunctionalTest
{

    public function test_required_elemements()
    {
        $this->login();

        $user = factory(User::class)->create();

        $this->sleep();

        $this->visit(route('hulpverleners.index'))
            ->click($user->fullname)
            ->seePageIs(route('hulpverleners.edit', [$user]));

        //empty all the elements and submit it to trigger all required alerts.
        $this->submitForm('Gegevens bewaren', [
            'firstname' => null,
            'lastname' => null,
            'phone' => null,
            'email' => null,
        ]);

        $this->seePageIs(route('hulpverleners.edit', [$user]))
            ->see('error-firstname')
            ->see('error-lastname')
            ->see('error-email');
    }

}
