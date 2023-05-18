<?php

namespace Modules\Demowebinar\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Modules\Demowebinar\Traits\ApiResponse;

class SurveyRequest extends FormRequest
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
            'name' => ['required', 'string', 'min:3', 'max:250'],
            'description' => ['required', 'string', 'max:250'],
            'questions' => ['required', 'array', 'min:1', 'max:10'],
            'questions.*.question' => ['required', 'string', 'min:3', 'max:250'],
            'questions.*.question_type_id' => ['required', Rule::in(1, 2, 3, 4)],
            'questions.*.description' => ['nullable', 'string', 'max:250'],
            'questions.*.options' => ['required_if:questions.*.question_type_id,1,2', 'array'],
            'questions.*.options.*.option' => ['required', 'string', 'min:2', 'max:250', 'distinct'],
            'questions.*.options.*.id' => ['nullable', 'integer']
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

    public function withValidator(Validator $validator)
    {
        if (!$validator->fails()) {
            $validator->sometimes(
                'options',
                function ($attribute, $value, $fail) {
                    $percentage = 0;
                    foreach ($value as $val) {
                        $percentage += $val['percentage'];
                    }
                    if ($percentage != 100) {
                        $fail('Total percentage must be 100.');
                    }
                },
                function ($input) {
                    return $input->fake_results === 1;
                }
            );

            $validator->sometimes(
                'options.*.percentage',
                'required|numeric|between:0,100',
                function ($input) {
                    return $input->fake_results === 1;
                }
            );
        }
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
