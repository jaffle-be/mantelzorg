<?php namespace App\Questionnaire\Export;

use App\System\Database\Eloquent\Model;
use App\System\Scopes\ModelAutoSort;

class Report extends Model
{
    use ModelAutoSort;

    protected $table = "questionnaire_reports";

    protected $fillable = ['filename', 'survey_count', 'questionnaire_id', 'organisation_id', 'user_id', 'created_at', 'updated_at'];

    public $autosort = 'created_at';

    public function questionnaire()
    {
        return $this->belongsTo('App\Questionnaire\Questionnaire', 'questionnaire_id');
    }

    public function organisation()
    {
        return $this->belongsTo('App\Organisation\Organisation', 'organisation_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

}