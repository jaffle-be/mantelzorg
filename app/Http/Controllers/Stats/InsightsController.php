<?php namespace App\Http\Controllers\Stats;

use App\Http\Controllers\AdminController;
use App\Mantelzorger\Oudere;
use App\Meta\Context;
use Input;
Use Lang;
use DB;

class InsightsController extends AdminController
{

    public function index()
    {
        return view('stats.insights');
    }

    public function ouderen(Oudere $ouderen, Context $meta)
    {
        $column = Input::get('field') . '_id';

        $stats = $ouderen
            ->groupBy($column)
            ->select([DB::raw('count(id) as value'), DB::raw($column . ' as label')])
            ->get();

        $context = $meta
            ->with(['values'])
            ->where('context', $column)
            ->first();

        $labels = $context->values->lists('value', 'id')->all();

        foreach ($stats as $stat) {
            if ($stat->label) {
                $stat->label = $labels[$stat->label];
            } else {
                $stat->label = Lang::get("stats.unanswered");
            }
        }

        return $stats->toJson();
    }

}