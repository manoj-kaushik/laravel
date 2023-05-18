<?php
namespace Modules\Demowebinar\Services\Webinar;

use \Illuminate\Support\Facades\Facade;

/**
 * Facade for survey service
 */
class SurveyFacade extends Facade
{

    /**
     * Returning service name
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'Modules\Demowebinar\Services\Webinar\SurveyService';
    }
}
