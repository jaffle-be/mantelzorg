<?php

namespace App\Http\Controllers;

use App\Organisation\OrganisationRepositoryInterface;
use App\Questionnaire\Export\Exporter;
use App\Questionnaire\Export\FileManager;
use App\Questionnaire\Export\Report;
use App\Questionnaire\Jobs\ExportJob;
use App\Questionnaire\Questionnaire;
use App\UserRepositoryInterface;
use Illuminate\Http\Request;
use Input;
use Lang;
use Redirect;
use Response;

class RapportController extends AdminController
{
    protected $search;

    /**
     * @var Questionnaire
     */
    protected $questionnaire;

    /**
     * @var Exporter
     */
    protected $export;

    protected $users;

    protected $organisations;

    public function __construct(Questionnaire $questionnaire, FileManager $files, UserRepositoryInterface $users, OrganisationRepositoryInterface $organisations)
    {
        $this->questionnaire = $questionnaire;
        $this->files = $files;
        $this->users = $users;
        $this->organisations = $organisations;

        $this->middleware('auth.admin');
    }

    public function index()
    {
        $questionnaires = $this->questionnaire->orderBy('created_at')->get();

        $questionnaires = ['' => Lang::get('rapport.select_survey')] + $questionnaires->lists('title', 'id')->all();

        $hulpverleners = $this->users->getForSelect();

        $organisations = $this->organisations->getForSelect();

        $reports = $this->files->listFiles();

        return view('rapport.index', compact('questionnaires', 'reports', 'hulpverleners', 'organisations'));
    }

    public function store(Request $request)
    {
        $id = Input::get('survey');

        $filters = Input::only(['hulpverlener_id', 'organisation_id']);

        $user = \Auth::user();

        $this->validate($request, ['survey' => 'required|exists:questionnaires,id']);

        $this->dispatchFromArray(ExportJob::class, ['id' => $id, 'userid' => $user->id, 'filters' => $filters]);

        return redirect()->back()->with('success', \Lang::get('rapport.success'));
    }

    public function show(Report $report)
    {
        if ($this->files->exists($report->filename)) {
            return Response::download(storage_path('exports/'.$report->filename));
        } else {
            return redirect()->route('report.index');
        }
    }

    public function destroy(Report $report)
    {
        $this->files->delete($report->filename);

        $report->delete();

        return json_encode(array(
            'status' => 'oke',
        ));
    }

    public function destroyBatch(Report $report, Request $request)
    {
        $ids = $request->get('ids', []);

        if (!empty($ids)) {
            $reports = Report::whereIn('id', $ids)->get();

            foreach ($reports as $report) {
                $this->files->delete($report->filename);

                $report->delete();
            }
        }

        return json_encode(array(
            'status' => 'oke',
        ));
    }
}
