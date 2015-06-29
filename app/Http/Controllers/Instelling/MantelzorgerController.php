<?php

namespace App\Http\Controllers\Instelling;

use App\Mantelzorger\Mantelzorger;
use Auth;
use Input;
use Redirect;

/**
 * Class MantelzorgerController
 *
 * @package App\Http\Controllers\Instelling
 */
class MantelzorgerController extends \App\Http\Controllers\AdminController
{

    /**
     * @var \App\Mantelzorger\Mantelzorger
     */
    protected $mantelzorger;

    /**
     * @param Mantelzorger $mantelzorger
     */
    public function __construct(Mantelzorger $mantelzorger)
    {
        $this->mantelzorger = $mantelzorger;

        $this->middleware('auth');
        $this->middleware('lock');
    }

    public function index($hulpverlener)
    {
        $query = Input::get('query');

        $search = $this->mantelzorger->search();

        $bool['must'] = [
            ['term' => ['hulpverlener_id' => $hulpverlener->id]]
        ];

        if (Input::get('query')) {
            $bool['should'] = [
                ['query' => ['match' => ['identifier.raw' => Input::get('query')]]],
                ['nested' => [
                    'path'  => 'oudere',
                    'query' => [
                        'match' => ['oudere.identifier.raw' => Input::get('query')]
                    ]
                ]]
            ];
        }

        $mantelzorgers = $search
            ->filterBool($bool)
            ->orderBy('identifier.raw', 'asc')
            ->get();

        $mantelzorgers->addQuery('query', $query);

        return view('instellingen.mantelzorgers.index', compact(array('hulpverlener', 'mantelzorgers')));
    }

    public function create($hulpverlener)
    {
        return view('instellingen.mantelzorgers.create', compact(array('hulpverlener')));
    }

    public function store($hulpverlener)
    {
        $input = Input::except('_token');

        $input['hulpverlener_id'] = $hulpverlener->id;

        $validator = $this->mantelzorger->validator($input, [], ['hulpverlener' => $hulpverlener->id]);

        if ($validator->fails()) {
            return Redirect::back()->withInput()->withErrors($validator->messages());
        }

        $mantelzorger = $this->mantelzorger->create($input);

        return Redirect::route('instellingen.{hulpverlener}.mantelzorgers.index', array($hulpverlener->id));
    }

    public function edit($hulpverlener, $mantelzorger)
    {
        $mantelzorger = $this->mantelzorger->find($mantelzorger);

        if ($mantelzorger) {
            return view('instellingen.mantelzorgers.edit', compact(array('hulpverlener', 'mantelzorger')));
        }
    }

    public function update($hulpverlener, $mantelzorger)
    {
        $mantelzorger = $this->mantelzorger->find($mantelzorger);

        if ($mantelzorger) {
            $input = Input::except('_token');

            $input['mantelzorger_id'] = $mantelzorger->id;
            $input['hulpverlener_id'] = $hulpverlener->id;

            $validator = $this->mantelzorger->validator($input, [], ['hulpverlener' => $hulpverlener->id, 'mantelzorger' => $mantelzorger->id]);

            if ($validator->fails()) {
                return Redirect::back()->withInput()->withErrors($validator->messages());
            } else {

                $mantelzorger->update($input);
            }
        }

        return Redirect::route('instellingen.{hulpverlener}.mantelzorgers.index', $hulpverlener->id);
    }

    public function delete()
    {
    }
}