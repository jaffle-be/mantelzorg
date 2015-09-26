<?php namespace App\Http\Controllers\Stats;

use App\Http\Controllers\AdminController;
use App\Questionnaire\Answer;
use App\Questionnaire\Choise;
use App\Questionnaire\Panel;
use App\Questionnaire\Question;
use App\Questionnaire\Questionnaire;
use DB;
use Illuminate\Http\Request;

class InsightsQuestionController extends AdminController
{

    public function __construct()
    {
        $this->middleware('auth.admin');
    }

    public function index()
    {
        $survey = Questionnaire::active()->first();

        return view('stats.insights-question', ['survey' => $survey]);
    }

    public function question(Panel $panel, Question $question, Request $request, Choise $choises, Answer $answers)
    {
        $this->validate($request, [
            'panel'    => 'required|exists:questionnaire_panels,id',
            'question' => 'exists:questionnaire_questions,id,questionnaire_panel_id,' . $request->get('panel')
        ]);

        if (!$request->get('question')) {

            $questions = $panel->find($request->get('panel'))->questions()->get();

            $question = $questions->first();
        } else {
            $question = $question->find($request->get('question'));
        }

        if ($question->multiple_choise) {
            $response = $this->handleMultipleChoise($answers, $question, $choises);
        } else {
            $response = $this->handleRegular($answers, $question);
        }

        //no no question was provided, we switched panels and we need to add the questions from the new panel to the response.
        if (!$request->get('question')) {
            $response['questions'] = $questions->map(function ($question) {
                return ['id' => $question->id, 'label' => $question->displayName];
            });
        }

        //always add the actual question
        $response['question'] = $question->load('choises')->toArray();

        $response['question']['meta'] = trim($response['question']['meta']);

        return json_encode($response);
    }

    //return the specifics of a significant term
    public function term(Answer $answer, Question $question, Request $request)
    {
        $question = $question->findOrFail($request->get('question'));

        $search = $answer->search();

        return $search->search('answers', [
            'index' => 'mantelzorg',
            'type'  => 'answers',
            'body'  => [
                "query"     => [
                    "bool" => [
                        "minimum_number_should_match" => 1,
                        "must"                        => [
                            [
                                "term" => [
                                    "question_id" => [
                                        "value" => $question->id
                                    ]
                                ]
                            ]
                        ],
                        "should"                      => [
                            [
                                "term" => [
                                    "explanation.dutch" => [
                                        "value" => $request->get('term')
                                    ]
                                ]
                            ]
                        ]
                    ]
                ],
                "highlight" => [
                    "pre_tags"  => ["<strong>"],
                    "post_tags" => ["</strong>"],
                    "fields"    => [
                        "explanation.dutch" => new \StdClass()
                    ]
                ]
            ]
        ], [], 15, function ($answer, $highlighting) {

            $answer->explanation = $highlighting['explanation.dutch'];
        });
    }

    protected function handleMultipleChoise(Answer $answers, Question $question, Choise $choises)
    {
        $results = $choises->join('questionnaire_answer_choises', 'questionnaire_answer_choises.choise_id', '=', 'questionnaire_choises.id')
            ->where('question_id', $question->id)
            ->groupBy('questionnaire_choises.id')
            ->get([
                'questionnaire_choises.title as label', DB::raw('count(questionnaire_answer_choises.answer_id) as value')
            ]);

        $terms = $this->mostSignificantTerms($answers, $question);

        return [
            'multiple_choise' => true,
            'answers'         => $results->toArray(),
            'terms'           => $terms,
        ];
    }

    protected function handleRegular(Answer $answers, Question $question)
    {
        $result = $this->mostSignificantTerms($answers, $question);

        return [
            'multiple_choise' => false,
            'terms'           => $result->toArray(),
        ];
    }

    /**
     * @param Answer  $answers
     * @param Request $request
     *
     * @return mixed
     */
    protected function mostSignificantTerms(Answer $answers, Question $question)
    {
        $result = $answers->search()->aggregate([
            'index' => 'mantelzorg',
            'type'  => 'answers',
            'body'  => [
                "aggs" => [
                    "top_terms" => [
                        "filter" => [
                            "term" => [
                                "question_id" => $question->id
                            ]
                        ],
                        "aggs"   => [
                            "top_terms" => [
                                "significant_terms" => [
                                    "field" => "explanation.dutch",
                                    "size"  => 20,
                                ]
                            ]
                        ]
                    ]
                ],
                "size" => 0
            ]
        ]);

        return $result;
    }

}