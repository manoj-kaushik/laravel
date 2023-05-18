<?php

namespace Modules\Demowebinar\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Demowebinar\Entities\SurveyQuestionOptions;
use Modules\Demowebinar\Entities\Master\QuestionTypes;
use Modules\Demowebinar\Traits\ModelSignature;

class SurveyQuestions extends Model
{
    //bootable trait for saving created_by and updated_by field values
    use ModelSignature;

    protected $fillable = ['survey_id', 'question_type_id', 'question', 'description', 'created_by', 'updated_by'];
    protected $table = 'demowebinar_survey_questions';

    public function options()
    {
        return $this->hasMany(SurveyQuestionOptions::class, 'question_id');
    }

    public function questionType()
    {
        return $this->belongsTo(QuestionTypes::class, 'question_type_id');
    }
}
