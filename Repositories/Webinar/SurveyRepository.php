<?php

namespace Modules\Demowebinar\Repositories\Webinar;

use Illuminate\Database\Eloquent\Model;
use Modules\Demowebinar\Entities\Demowebinar;
use Modules\Demowebinar\Entities\Survey;
use Modules\Demowebinar\Entities\SurveyQuestions;
use Modules\Demowebinar\Entities\SurveyQuestionOptions;
use Modules\Demowebinar\Entities\PollsOptions;
use Modules\Demowebinar\Entities\SurveyFakeResult;
use Modules\Demowebinar\Entities\SurveySubmission;
use Modules\Demowebinar\Entities\SurveySubmissionAnswers;

/**
 * Layer to handle datastore operations. Can be a local operation or external datastore
 */
class SurveyRepository implements SurveyInterface
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
     * @param Model $Survey
     * @return SurveyRepository
     */
    public function __construct(Survey $Survey)
    {
        $this->survey = $Survey;
    }

    public function getSurveys($filters)
    {
        if (!empty($filters['short'])) {
            $surveys = Survey::withCount('questions');
        } else {
            $surveys = Survey::with('questions', 'questions.questionType', 'questions.options');
        }
        return $surveys->orderBy('created_at', 'desc')->get();
    }

    public function saveSurveyDetails($data, $id)
    {
        if ($id == null) {
            $survey = new Survey();
        } else {
            $survey = Survey::findOrFail($id);
        }

        $survey->name = $data['name'] ?? null;
        $survey->description = $data['description'] ?? null;
        $saved = $survey->save();
        return $survey;
    }

    public function saveQuestionDetails($data, $surveyId)
    {

        if (!empty($data['id'])) {
            $question = SurveyQuestions::findorFail($data['id']);

            //Delete old/unrequired options
            if (in_array($question->question_type_id, [1, 2]) && in_array($data['question_type_id'], [3, 4])) {
                $options = $question->options();
                if ($options->exists()) {
                    $options->delete();
                }
            }
        } else {
            $question = new SurveyQuestions();
        }


        $question->survey_id = $surveyId;
        $question->question_type_id = $data['question_type_id'] ?? null;
        $question->question = $data['question'] ?? null;
        $question->description = $data['description'] ?? null;
        $saved = $question->save();


        return $question;
    }

    public function saveOptionsDetails($data, $questionId)
    {
        $options = $optionIds = [];
        foreach ($data['options'] as $key => $value) {
            if (!empty($value['option'])) {

                if (!empty($value['id'])) {
                    $option = SurveyQuestionOptions::findOrFail($value['id']);
                } else {
                    $option = new SurveyQuestionOptions();
                }
                $option->question_id = $questionId;
                $option->option = $value['option'];
                $saved = $option->save();
                if (!$saved) {
                    return false;
                }

                $options[] = $option;
                $optionIds[] = $option->id;
            }
        }

        //Delete previous options
        $options = SurveyQuestionOptions::where('question_id', $questionId)
            ->whereNotIn('id', $optionIds)->delete();

        return $options;
    }

    public function updateOptionsDetails($data, $surveyId)
    {
        $optionIds = [];
        foreach ($data['options'] as $key => $value) {
            if (!empty($value['id'])) {
                $optionIds[] = $value['id'];
            }
        }
        $options = PollsOptions::where('survey_id', $surveyId)->whereNotIn('id', $optionIds);
        if ($data['fake_results'] == 1) {
            $ids = $options->pluck('id')->toArray();
        } else {
            $ids = PollsOptions::where('survey_id', $surveyId)->pluck('id')->toArray();
        }
        $fakeResult = SurveyFakeResult::whereIn('option_id', $ids)->delete();
        $options->delete();
        $options = [];
        foreach ($data['options'] as $key => $value) {
            if (array_key_exists('id', $value)) {
                $option = PollsOptions::findOrFail($value['id']);
            } else {
                $option = new PollsOptions();
            }
            $option->survey_id = $surveyId;
            $option->option = $value['option'];
            $saved = $option->save();
            if (!$saved) {
                return false;
            }
            if ($data['fake_results'] == 1) {
                $fakeResult = null;
                if (array_key_exists('id', $value)) {
                    $fakeResult = SurveyFakeResult::where('option_id', $value['id'])->first();
                }
                if (empty($fakeResult)) {
                    $fakeResult = new SurveyFakeResult();
                }
                $fakeResult->option_id = $option->id;
                $fakeResult->percentage = $value['percentage'];
                $saved = $fakeResult->save();
                if (!$saved) {
                    return false;
                }
                $option->percentage = $fakeResult->percentage;
            }
            $options[] = $option;
        }
        return $options;
    }

    /**
     * Get Survey details
     *
     * @param integer $surveyId
     * @return object
     */
    public function getSurveyDetails($surveyId)
    {
        $survey = Survey::with('questions', 'questions.questionType', 'questions.options')
            ->where('id', $surveyId)
            ->first();

        return $survey;
    }

    public function deleteSurvey($id)
    {
        $survey = Survey::findOrFail($id);

        $questions = $survey->questions();

        if ($questions->exists()) {

            //Delete question options
            foreach ($questions as $question) {
                $options = $question->options();
                if ($options->exists()) {
                    $options->delete();
                }
            }
            //Delete questions
            $questions->delete();
        }

        //Delete survey
        $survey->delete();

        return $survey;
    }

    /**
     * Get Survey question details
     *
     * @param integer $surveyId
     * @return object
     */
    public function getSurveyQuestionDetails($questionId)
    {
        $question = Survey::with('questionType', 'options')
            ->where('id', $questionId)
            ->first();

        return $question;
    }

    public function deleteQuestion($surveyId, $questionId)
    {
        $question = SurveyQuestions::findOrFail($questionId);

        if ($question->survey_id == $surveyId) {

            //Delete related options
            $question->options()->delete();

            $question->delete();
        } else {
            throw new \Exception("Invalid request.");
        }

        return $question;
    }

    public function submitSurvey($data, $webinarPermalink)
    {
        //Get webinar
        $webinar = Demowebinar::where('permalink', $webinarPermalink)->firstOrFail();

        //Get Schedule
        $schedule = $webinar->seriesSchedule()->where('permalink', $data['schedule_permalink'])->firstOrFail();

        //Get registrant
        $registrant = $schedule->registrants()->where('permalink', $data['registrant_permalink'])->firstOrFail();

        //Save user submission details
        return SurveySubmission::create([
            'webinar_id' => $webinar->id,
            'schedule_id' => $schedule->id,
            'survey_id' => $data['survey_id'],
            'registrant_id' => $registrant->id
        ]);
    }

    public function submitSurveyAnswer($question, $submissionId)
    {
        //Save survey submitted answer details
        $response = SurveySubmissionAnswers::create([
            'submission_id' => $submissionId,
            'question_id' => $question['question_id'],
            'selected_option' => $question['option_id'] ?? null,
            'answer' => $question['answer'] ?? null
        ]);

        return $response;
    }
}
