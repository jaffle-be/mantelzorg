<?php namespace Questionnaire\Export;

use Illuminate\Database\Query\JoinClause;
use Organisation\OrganisationRepositoryInterface;
use Questionnaire\Questionnaire;
use UserRepositoryInterface;

class SessionFilter {

    protected $users;

    public function __construct(UserRepositoryInterface $users, OrganisationRepositoryInterface $organisations){
        $this->users = $users;
    }

    public function filter(Questionnaire $survey, $filters){

        $query = $survey->sessions();

        if($filters['hulpverlener_id'])
        {
            $query->where('user_id', $filters['hulpverlener_id']);
        }

        if($filters['organisation_id'])
        {
            $query->join('users', function($join) use ($filters){

                /** @var JoinClause $join */
                $join->on('users.id', '=', 'questionnaire_survey_sessions.user_id')
                    ->where('users.organisation_id', '=', $filters['organisation_id']);

            });
        }

        $query->select(['questionnaire_survey_sessions.*']);

        return $query;
    }

}