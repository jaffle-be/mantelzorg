<?php

namespace App\Mantelzorger\Commands;

use App\Commands\Command;
use App\Mantelzorger\Mantelzorger;
use App\Mantelzorger\Oudere;

class NewOudere extends Command
{
    /**
     * @var Mantelzorger
     */
    private $mantelzorger;
    /**
     * @var array
     */
    private $input;

    public function __construct(Mantelzorger $mantelzorger, array $input)
    {
        $this->mantelzorger = $mantelzorger;
        $this->input = $input;
    }

    public function handle(Oudere $oudere)
    {
        $input = array_merge(['mantelzorger_id' => $this->mantelzorger->id], $this->input);

        return $oudere->create($input);
    }
}
