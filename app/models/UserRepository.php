<?php

class UserRepository implements UserRepositoryInterface{

    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getForSelect()
    {
        return $this->user->newQuery()->select([DB::raw("concat(`firstname`, ' ', `lastname`) as fullname"), 'id', 'firstname', 'lastname'])->orderBy('firstname')->orderBy('lastname')->lists('fullname', 'id');
    }

    public function findByOrganisation($organisation)
    {
        $this->user->newQuery()->where('organisation_id', $organisation);
    }
}