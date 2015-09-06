<?php

namespace App\Mantelzorger\Commands;

use App\Commands\Command;
use App\Mantelzorger\Mantelzorger;
use App\Mantelzorger\Oudere;
use Illuminate\Contracts\Bus\SelfHandling;

class UpdateOudere extends Command implements SelfHandling{

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

    public function handle(Oudere $oudere)
    {
        return $oudere->update($this->input);
    }

}