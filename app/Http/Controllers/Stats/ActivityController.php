<?php namespace App\Http\Controllers\Stats;

use App\Http\Controllers\AdminController;
use App\Organisation\Organisation;
use App\Questionnaire\Questionnaire;
use App\Questionnaire\Session;
use Carbon\Carbon;
use DB;

class ActivityController extends AdminController
{

    public function __construct()
    {
        $this->middleware('auth.admin');
    }

    public function index()
    {
        return view('stats.activity');
    }

    public function sessions(Session $surveys)
    {
        $surveys = $surveys
            ->groupBy('date')
            ->select([DB::raw('count(id) as value'), DB::raw("date_format(created_at, '%j') as date")])
            ->whereRaw('date_sub(now(), interval 6 month) < created_at')
            ->lists('value', 'date')->all();

        $now = Carbon::now();

        $start = clone $now;
        $start->subMonth(6);

        $stats = array();

        while ($start->dayOfYear < $now->dayOfYear) {
            $stamp = $start->dayOfYear;

            $value = isset($surveys[$stamp]) ? $surveys[$stamp] : 0;

            $stats[] = [
                'day'   => $start->format('Y-m-d'),
                'value' => $value
            ];

            $start->addDay();
        }

        return json_encode($stats);
    }

    public function organisations(Questionnaire $survey, Organisation $organisation)
    {
        //we want the amount of sessions per organisation
        $survey = $survey->active()->first();

        return $organisation
            ->join('users', 'organisations.id', '=', 'users.organisation_id')
            ->join('questionnaire_survey_sessions', 'users.id', '=', 'questionnaire_survey_sessions.user_id')
            ->where('questionnaire_id', $survey->id)
            ->groupBy('organisations.id')
            ->get([
                'organisations.id', 'organisations.name', DB::raw('count(questionnaire_survey_sessions.id) as count')
            ]);
    }

}