<?php

namespace App\Mantelzorger\Commands;

use App\Commands\Command;
use App\Mantelzorger\Mantelzorger;
use App\Mantelzorger\Oudere;

class UpdateOudere extends Command
{
    /**
     * @var Mantelzorger
     */
    private $mantelzorger;
    /**
     * @var Oudere
     */
    private $oudere;
    /**
     * @var array
     */
    private $input;

    public function __construct(Mantelzorger $mantelzorger, Oudere $oudere, array $input)
    {
        $this->mantelzorger = $mantelzorger;
        $this->oudere = $oudere;
        $this->input = $input;
    }

    public function handle()
    {
        return $this->oudere->update($this->input);
    }
}
