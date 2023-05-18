<?php

namespace Modules\Demowebinar\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Modules\Demowebinar\Traits\ApiResponse;

class SurveyQuestionRequest extends FormRequest
{
    use ApiResponse;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'question' => ['required','string','min:5','max:250'],
            'question_type_id' => ['required',Rule::in(1,2,3,4)],
            'description' => ['nullable','string','max:250'],
            'options' => ['required_if:question_type_id,1,2','array'],
            'options.*.option' => ['required','distinct','string']
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the failed validation response for the request.
     *
     * @param array $errors
     * @return JsonResponse
     */
    public function response($errors)
    {
        $transformed = [];

        foreach ($errors as $field => $messages) {
            $transformed[] = [
                'field' => $field,
                'message' => $message
            ];
        }

        return response()->json([
            'errors' => $transformed
        ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
    }

}
