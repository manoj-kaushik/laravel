<?php

namespace Modules\Demowebinar\Services\Webinar;

use Modules\Demowebinar\Repositories\Webinar\SurveyInterface;
use Modules\Demowebinar\Entities\Survey;

/**
 * Layer to call and perform datastore operations
 */
class SurveyService
{

    /**
     * Variable to hold injected dependency
     *
     * @var [type]
     */
    protected $survey;

    /**
     * Initializing the instances and variables
     *
     * @param SurveyInterface $survey
     */
    public function __construct(SurveyInterface $survey)
    {
        $this->survey = $survey;
    }

    public function index($filters = array())
    {
        return $this->survey->getSurveys($filters);
    }

    public function store($data, $id = null)
    {
        $survey = $this->survey->saveSurveyDetails($data, $id);

        //Save question details
        if (!empty($data['questions'] && !empty($survey->id))) {
            foreach ($data['questions'] as $question) {
                $surveyQuestion = $this->survey->saveQuestionDetails($question, $survey->id);

                //Save option details
                if (in_array($surveyQuestion['question_type_id'], [1, 2]) && !empty($surveyQuestion->id)) {
                    $options = $this->survey->saveOptionsDetails($question, $surveyQuestion->id);
                    $survey->options = $options;
                }
            }

            //Get Survey complete object
            $surveyDetails = $this->survey->getSurveyDetails($survey->id);

            return $surveyDetails;
        }

        return false;
    }

    public function show($surveyId)
    {
        return $this->survey->getSurveyDetails($surveyId);
    }

    public function update($data, $surveyId)
    {
        $survey = $this->survey->saveSurveyDetails($data, $surveyId);
        if ($data['is_survey'] == 0 || $data['type'] == 'poll_with_one_answer' || $data['type'] == 'poll_with_multiple_answers') {
            $options = $this->survey->updateOptionsDetails($data, $surveyId);
            $survey->options = $options;
        }
        return $survey;
    }

    public function destroy($id)
    {
        return $this->survey->deleteSurvey($id);
    }


    public function storeQuestion($data, $id = null)
    {
        $survey = $this->survey->saveSurveyDetails($data, $id);

        //Save question details
        if (!empty($data['questions'] && !empty($survey->id))) {
            foreach ($data['questions'] as $question) {
                $surveyQuestion = $this->survey->saveQuestionDetails($question, $survey->id);

                //Save option details
                if (in_array($surveyQuestion['question_type_id'], [1, 2]) && $surveyQuestion->id) {
                    $options = $this->survey->saveOptionsDetails($data, $surveyQuestion->id);
                    $survey->options = $options;
                }
            }

            //Get Survey complete object
            $surveyDetails = $this->survey->surveyDetails();

            return $surveyDetails;
        }

        return false;
    }

    public function updateQuestion($data, $surveyId)
    {
        $surveyQuestion = $this->survey->saveQuestionDetails($data, $surveyId);

        if (!empty($data['options']) && isset($surveyQuestion->id)) {
            $options = $this->survey->updateOptionsDetails($data, $surveyQuestion->id);
        }

        $question = $this->survey->getSurveyQuestionDetails($surveyQuestion->id);

        return $question;
    }

    public function destroyQuestion($surveyId, $questionId)
    {
        return $this->survey->deleteQuestion($surveyId, $questionId);
    }


    public function SubmitSurvey($data, $webinarPermalink)
    {
        $surveySubmit = $this->survey->submitSurvey($data, $webinarPermalink);
        if (!empty($surveySubmit)) {
            $response = array();
            foreach ($data['questions'] as $question) {
                if ($question['type'] == 2) {
                    foreach ($question['checked_options'] as $option) {
                        $question['option_id'] = $option;
                        $response = $this->survey->submitSurveyAnswer($question, $surveySubmit->id);
                    }
                } else {
                    $response = $this->survey->submitSurveyAnswer($question, $surveySubmit->id);
                }
            }
            return $response;
        }
    }
}
