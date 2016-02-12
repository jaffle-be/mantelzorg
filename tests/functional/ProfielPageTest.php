<?php namespace Test\Functional;

use Test\FunctionalTest;

class ProfielPageTest extends FunctionalTest
{

    public function test_seeing_my_profile()
    {
        $user = $this->login();

        $this->visit(route('instellingen.index'))
            ->see(htmlspecialchars($user->firstname))
            ->see(htmlspecialchars($user->lastname))
            ->see($user->email)
            ->see($user->phone);

        $this->myChecked('male', $user->male);
        $this->isSelected('organisation_id', $user->organisation_id);
        $this->isSelected('organisation_location_id', $user->organisation_location_id);
    }

}