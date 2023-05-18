<?php

namespace Modules\Demowebinar\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Modules\Demowebinar\Http\Requests\SurveyRequest;
use Modules\Demowebinar\Transformers\Survey\SurveyResource;
use Modules\Demowebinar\Transformers\Survey\SurveyCollection;
use Modules\Demowebinar\Services\Webinar\SurveyFacade;
use Modules\Demowebinar\Entities\Demowebinar;
use Modules\Demowebinar\Transformers\Webinar\WebinarResource;
use Modules\Demowebinar\Helpers\ExceptionHelper;


class SurveyController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        try {

            //Get request data
            $request = Request();
            $filters = $request->all();

            //Get surveys
            $surveys = SurveyFacade::index($filters);
            return new SurveyCollection($surveys);

        } catch (\Exception $exception) {
            $statusCode = ExceptionHelper::errorLog($exception);
            return response()->json(array('status' => false, 'data' => array(), 'message' => '', 'errors' => array()), $statusCode);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(SurveyRequest $request)
    {
        try {
            $data = $request->validated();
            $survey = SurveyFacade::store($data);
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
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($surveyId)
    {
        try
        {
            $survey = SurveyFacade::show($surveyId);
            if (!empty($survey)) {
                return new SurveyResource($survey);
            }
            return response()->json(['message' => 'Survey not found'], 404);
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
    public function update(SurveyRequest $request, $surveyId)
    {
        try {
            $data = $request->validated();
            $survey = SurveyFacade::store($data, $surveyId);
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
    public function destroy($surveyId)
    {
        try {
            $survey = SurveyFacade::destroy($surveyId);

            if ($survey) {
                return response()->json(null, 204);
            }
        } catch (\Exception $exception) {
            $statusCode = ExceptionHelper::errorLog($exception);
            return response()->json(array('status' => false, 'data' => array(), 'message' => '', 'errors' => array()), $statusCode);
        }
    }
}
