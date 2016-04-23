<?php

namespace App\Mantelzorger\Commands;

use App\Commands\Command;
use App\Mantelzorger\Mantelzorger;
use App\User;

class UpdateMantelzorger extends Command
{
    /**
     * @var User
     */
    protected $hulpverlener;

    protected $mantelzorger;

    /**
     * @var array
     */
    protected $input;

    public function __construct(User $hulpverlener, Mantelzorger $mantelzorger, array $input)
    {
        $this->hulpverlener = $hulpverlener;
        $this->mantelzorger = $mantelzorger;
        $this->input = $input;
    }

    public function handle()
    {
        $this->input['mantelzorger_id'] = $this->mantelzorger->id;

        $this->input['hulpverlener_id'] = $this->hulpverlener->id;

        $this->mantelzorger->update($this->input);
    }
}
