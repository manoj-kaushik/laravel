<?php

namespace Modules\Demowebinar\Transformers\Survey;

use Illuminate\Http\Resources\Json\Resource;
use Modules\Demowebinar\Transformers\Survey\SurveyQuestionsCollection;

class SurveyResource extends Resource
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
            'name' => $this->name,
            'description' => $this->description,
            'questions' => SurveyQuestionsResource::collection($this->whenLoaded('questions')),
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
        return $resource_array;
    }
}
