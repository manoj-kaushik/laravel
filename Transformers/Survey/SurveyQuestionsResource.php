<?php

namespace Modules\Demowebinar\Transformers\Survey;

use Illuminate\Http\Resources\Json\Resource;
use Modules\Demowebinar\Transformers\Survey\SurveyQuestionOptionsResource;
use Modules\Demowebinar\Transformers\Master\MasterResource;

class SurveyQuestionsResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {

        $resource_array = [
            'id' => $this->id,
            'survey_id' => $this->survey_id,
            'question_type_id' => $this->question_type_id,
            'question_type' => new MasterResource($this->whenLoaded('questionType')),
            'question' => $this->question,
            'description' => $this->description,
            'options' => SurveyQuestionOptionsResource::collection($this->whenLoaded('options')),
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
        return $resource_array;
    }
}
