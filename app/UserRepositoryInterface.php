<?php

namespace App;
interface UserRepositoryInterface
{

    public function findByOrganisation($organisation);
}