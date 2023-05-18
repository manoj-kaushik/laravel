<?php

namespace Modules\Demowebinar\Repositories\Webinar;

/**
 * Interface functions for survey repository
 */
interface SurveyInterface
{
    /**
     * Fetching survey id for demonstration
     *
     * @param integer $surveyId
     * @return string
     */
    // public function getSurvey($surveyId);
    public function getSurveys($filters);
    public function saveSurveyDetails($data, $surveyId);
    public function saveOptionsDetails($data, $surveyId);
    public function updateOptionsDetails($data, $surveyId);
    public function getSurveyDetails($surveyId);
    public function deleteSurvey($id);
}
