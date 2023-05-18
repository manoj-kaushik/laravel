<?php

namespace Modules\Demowebinar\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Demowebinar\Http\Requests\SurveyQuestionRequest;
use Modules\Demowebinar\Transformers\Survey\SurveyResource;
use Modules\Demowebinar\Transformers\Survey\SurveyQuestionsResource;
use Modules\Demowebinar\Transformers\Survey\SurveyCollection;
use Modules\Demowebinar\Services\Webinar\SurveyFacade;
use Modules\Demowebinar\Entities\Demowebinar;
use Modules\Demowebinar\Entities\SurveyQuestions;
use Modules\Demowebinar\Transformers\Webinar\WebinarResource;
use Modules\Demowebinar\Helpers\ExceptionHelper;


class SurveyQuestionController extends Controller
{
    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(SurveyQuestionRequest $request, $surveyId)
    {
        try {
            $data = $request->validated();
            $survey = SurveyFacade::storeQuestion($data);
            if (!$survey) {
                return response()->json(['error' => 'Unknown Backend Error Occured. Please try again'], 500);
            }
            return new SurveyResource($survey);
        } catch (\Exception $exception) {
            $statusCode = ExceptionHelper::errorLog($exception);
            return response()->json(array('status' => false, 'data' => array(), 'message' => '', 'errors' => array()), $statusCode);
        }
    }

    public function show($surveyId, $questionId)
    {
        try
        {
            $data = SurveyQuestions::findOrFail($questionId);
            return new SurveyQuestionsResource($data);
        }
        catch(\Exception $exception)
        {
            $statusCode = ExceptionHelper::errorLog($exception);
            return response()->json(array('status' => false, 'data' => array(), 'message' => '', 'errors' => array()), $statusCode);
        }
    }
    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(SurveyQuestionRequest $request, $surveyId)
    {
        try {
            $data = $request->validated();
            $survey = SurveyFacade::updateQuestion($data, $surveyId);
            if (!$survey) {
                return response()->json(['error' => 'Unknown Backend Error Occured. Please try again'], 500);
            }
            return new SurveyResource($survey);
        } catch (\Exception $exception) {
            $statusCode = ExceptionHelper::errorLog($exception);
            return response()->json(array('status' => false, 'data' => array(), 'message' => '', 'errors' => array()), $statusCode);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($surveyId, $questionId)
    {
        try {
            $survey = SurveyFacade::destroyQuestion($surveyId, $questionId);
            if ($survey) {
                return response()->json(null, 204);
            }
        } catch (\Exception $exception) {
            $statusCode = ExceptionHelper::errorLog($exception);
            return response()->json(array('status' => false, 'data' => array(), 'message' => '', 'errors' => array()), $statusCode);
        }
    }
}
