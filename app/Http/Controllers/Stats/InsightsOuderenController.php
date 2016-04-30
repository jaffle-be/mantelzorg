<?php

namespace App\Http\Controllers\Stats;

use App\Http\Controllers\AdminController;
use App\Meta\Context;
use App\Search\SearchServiceInterface;

class InsightsOuderenController extends AdminController
{
    public function __construct()
    {
        $this->middleware('auth.admin');
    }

    public function index()
    {
        return view('stats.insights-ouderen');
    }

    public function ouderen(SearchServiceInterface $search)
    {
        $results = $search->aggregate([
            'index' => config('search.index'),
            'type' => 'mantelzorgers',
            'body' => [
                'aggs' => [
                    'ouderen' => [
                        'nested' => [
                            'path' => 'oudere',
                        ],
                        'aggs' => [
                            'mantelzorger_relation' => [
                                'terms' => [
                                    'field' => 'oudere.mantelzorger_relation_id',
                                    'size' => 100,
                                ],
                            ],
                            'ouderen_woonsituatie' => [
                                'terms' => [
                                    'field' => 'oudere.woonsituatie_id',
                                    'size' => 100,
                                ],
                            ],
                            'oorzaak_hulpbehoefte' => [
                                'terms' => [
                                    'field' => 'oudere.oorzaak_hulpbehoefte_id',
                                    'size' => 100,
                                ],
                            ],
                            'bel_profiel' => [
                                'terms' => [
                                    'field' => 'oudere.bel_profiel_id',
                                    'size' => 100,
                                ],
                            ],
                        ],
                    ],
                ],
                'size' => 0,
            ],
        ]);

        $results = $results['ouderen'];

        $this->setLabels(Context::MANTELZORGER_RELATION, $results['mantelzorger_relation']);
        $this->setLabels(Context::OUDEREN_WOONSITUATIE, $results['ouderen_woonsituatie']);
        $this->setLabels(Context::OORZAAK_HULPBEHOEFTE, $results['oorzaak_hulpbehoefte']);
        $this->setLabels(Context::BEL_PROFIEL, $results['bel_profiel']);

        return $results;
    }

    protected function setLabels($name, &$results)
    {
        $values = Context::where('context', $name)->first()->values->pluck('value', 'id');

        foreach ($results['buckets'] as $index => $result) {
            $result['key'] = $values->get($result['key']);

            $results['buckets'][$index] = [
                'value' => $result['doc_count'],
                'label' => $result['key'],
            ];
        }
    }
}
