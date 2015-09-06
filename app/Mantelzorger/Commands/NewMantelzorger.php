<?php namespace App\Mantelzorger\Commands;

use App\Commands\Command;
use App\Mantelzorger\Mantelzorger;
use App\User;
use Illuminate\Contracts\Bus\SelfHandling;

class NewMantelzorger extends Command implements SelfHandling
{

    public function __construct(User $user, $input)
    {
        $this->user = $user;
        $this->input = $input;
    }

    public function handle(Mantelzorger $mantelzorger)
    {
        $input = array_merge($this->input, ['hulpverlener_id' => $this->user->id]);

        $validator = $mantelzorger->validator($input, [], ['hulpverlener' => $this->user->id]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator->messages());
        }

        $mantelzorger = $mantelzorger->create($input);

        return $mantelzorger;
    }
}