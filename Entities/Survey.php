<?php

namespace Modules\Demowebinar\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Demowebinar\Entities\SurveyQuestions;
use Modules\Demowebinar\Entities\Demowebinar;
use Modules\Demowebinar\Traits\ModelSignature;
use Modules\Demowebinar\Scopes\UserScope;

class Survey extends Model
{
    //bootable trait for saving created_by and updated_by field values
    use ModelSignature;

    protected $fillable = ['name', 'description', 'created_by', 'updated_by'];
    protected $table = 'demowebinar_surveys';

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new UserScope);
    }


    public function questions()
    {
        return $this->hasMany(SurveyQuestions::class, 'survey_id');
    }

    /**
     * The users that belong to the role.
     */
    public function webinars()
    {
        return $this->belongsToMany(Demowebinar::class);
    }
}
