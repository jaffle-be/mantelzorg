<?php

namespace App\Http\Controllers\Instelling;

use App\Mantelzorger\Commands\NewMantelzorger;
use App\Mantelzorger\Commands\SearchMantelzorgers;
use App\Mantelzorger\Commands\UpdateMantelzorger;
use App\Mantelzorger\Mantelzorger;
use App\Mantelzorger\Request\NewMantelzorgerRequest;
use App\Mantelzorger\Request\UpdateMantelzorgerRequest;
use App\User;
use Auth;
use Input;
use Redirect;

/**
 * Class MantelzorgerController.
 */
class MantelzorgerController extends \App\Http\Controllers\AdminController
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('lock');
    }

    public function index(User $hulpverlener)
    {
        $mantelzorgers = $this->dispatchFromArray(SearchMantelzorgers::class, [
            'user' => $hulpverlener,
            'query' => Input::get('query'),
        ]);

        return view('instellingen.mantelzorgers.index', compact('hulpverlener', 'mantelzorgers'));
    }

    public function create(User $hulpverlener)
    {
        return view('instellingen.mantelzorgers.create', compact('hulpverlener'));
    }

    public function store(User $hulpverlener, NewMantelzorgerRequest $request)
    {
        $mantelzorger = $this->dispatchFromArray(NewMantelzorger::class, [
            'user' => $hulpverlener,
            'input' => $request->except('_token'),
        ]);

        if ($mantelzorger) {
            return Redirect::route('instellingen.{hulpverlener}.mantelzorgers.index', array($hulpverlener->id));
        } else {
            return redirect()->back();
        }
    }

    public function edit(User $hulpverlener, Mantelzorger $mantelzorger)
    {
        return view('instellingen.mantelzorgers.edit', compact('hulpverlener', 'mantelzorger'));
    }

    public function update(User $hulpverlener, Mantelzorger $mantelzorger, UpdateMantelzorgerRequest $request)
    {
        $this->dispatchFromArray(UpdateMantelzorger::class, [
            'mantelzorger' => $mantelzorger,
            'hulpverlener' => $hulpverlener,
            'input' => $request->except('_token', '_method'),
        ]);

        return Redirect::route('instellingen.{hulpverlener}.mantelzorgers.index', $hulpverlener->id);
    }
}
