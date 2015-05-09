<?php

use Mantelzorger\Oudere;
use Meta\Context;

class StatsController extends AdminController{

    protected $ouderen;

    public function __construct(Oudere $ouderen, Context $meta)
    {
        $this->ouderen = $ouderen;

        $this->meta = $meta;
    }

    public function index()
    {
        $this->layout->content = View::make('stats.index');
    }

    public function stats()
    {
        $column = Input::get('field') . '_id';

        $stats = $this->ouderen
            ->groupBy($column)
            ->select([DB::raw('count(id) as value'), DB::raw($column . ' as label')])
            ->get();

        $context = $this->meta
            ->with(['values'])
            ->where('context', $column)
            ->first();

        $labels = $context->values->lists('value', 'id');

        foreach($stats as $stat)
        {
            if($stat->label)
            {
                $stat->label = $labels[$stat->label];
            }
            else
            {
                $stat->label = Lang::get("stats.unanswered");
            }
        }

        return $stats->toJson();
    }

}