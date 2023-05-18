<?php

namespace Modules\Demowebinar\Transformers\Survey;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Demowebinar\Traits\ApiResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SurveyCollection extends ResourceCollection
{
    use ApiResponse;
    /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public $collects = 'Modules\Demowebinar\Transformers\Survey\SurveyResource';

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection
        ];
    }

}






