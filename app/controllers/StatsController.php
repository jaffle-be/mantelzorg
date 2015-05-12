<?php

use Carbon\Carbon;
use Mantelzorger\Oudere;
use Meta\Context;
use Questionnaire\Session;

class StatsController extends AdminController{

    protected $ouderen;

    protected $meta;

    protected $surveys;

    public function __construct(Oudere $ouderen, Context $meta, Session $surveys)
    {
        $this->ouderen = $ouderen;

        $this->meta = $meta;

        $this->surveys = $surveys;
    }

    public function index()
    {
        $this->layout->content = View::make('stats.index');
    }

    public function ouderen()
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

    public function sessions()
    {
        $surveys = $this->surveys
            ->groupBy('date')
            ->select([DB::raw('count(id) as value'), DB::raw("date_format(created_at, '%j') as date")])
            ->whereRaw('date_sub(now(), interval 1 month) < created_at')
            ->lists('value', 'date');

        $now = Carbon::now();

        $start = clone $now;
        $start->subMonth();

        $stats = array();


        while($start->dayOfYear < $now->dayOfYear)
        {
            $stamp = $start->dayOfYear;

            $value = isset($surveys[$stamp]) ? $surveys[$stamp] : 0;

            $stats[] = [
                'day' => $start->format('Y-m-d'),
                'value' => $value
            ];

            $start->addDay();
        }

        return json_encode($stats);
    }

}