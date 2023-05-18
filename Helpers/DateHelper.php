<?php

namespace Modules\Demowebinar\Helpers;

use Config;
use DateTime;
use DateInterval;
use Carbon\Carbon;

class DateHelper
{

    public static $dbDateTimeFormat = 'Y-m-d H:i:s';
    public static $dbDateFormat = 'Y-m-d';
    public static $dbTimeFormat = 'H:i:s';
    public static $userDateFormat = 'm/d/Y';
    public static $formDateFormat = 'm/d/Y';
    public static $userDateTimeFormat = 'j M, Y, g:i A';
    public static $userTimeFormat = 'g:i A';
    public static $userMonthYearFormat = 'M Y';
    public static $userYearOnlyFormat = 'Y';

    /**
     * This function will be used to set Date format.
     *
     * @param date $date
     * @return date $date
     */
    public static function showDate($date = '0')
    {
        //Get Data from object
        if (is_object($date)) {
            $date = (string) $date;
        }

        //Changing date format and return 0 incase og wrong date
        if (strtotime($date) && $date != 0) {
            $date = date(self::$userDateFormat, strtotime($date));
        } else {
            $date = 'NA';
        }

        return $date;
    }

    /**
     * This function will be used to set Date format for form fields.
     *
     * @param date $date
     * @return date $date
     */
    public static function showFormDate($date = '0')
    {
        //Get Data from object
        if (is_object($date)) {
            $date = (string) $date;
        }

        //Changing date format and return 0 incase og wrong date
        if (strtotime($date) && $date != 0) {
            $date = date(self::$formDateFormat, strtotime($date));
        } else {
            $date = '';
        }

        return $date;
    }

    /**
     * This function will be used to set Date & Time format.
     *
     * @param date $date
     * @return date $date
     */
    public static function showDateTime($date = 0)
    {

        //Get Data from object
        if (is_object($date)) {
            $date = (string) $date;
        }

        //Changing date format and return 0 incase og wrong date
        if (strtotime($date)) {
            $date = date(self::$userDateTimeFormat, strtotime($date));
        } else {
            $date = 'NA';
        }

        return $date;
    }

    /**
     * This function will be used to get Time format.
     *
     * @param date $date
     * @return date $date
     */
    public static function showTime($time = 0)
    {

        //Get Data from object
        if (is_object($time)) {
            $time = (string) $time;
        }

        //Changing date format and return 0 incase og wrong date
        if (strtotime($time)) {
            $time = date(self::$userTimeFormat, strtotime($time));
        } else {
            $time = 'NA';
        }

        return $time;
    }

    /**
     * This function will be used to set Date format in Mysql server format.
     *
     * @param date $date
     * @return date $date
     */
    public static function setDbDateTime($date)
    {
        //Changing date format and return 0 incase og wrong date
        if (strtotime($date)) {
            $date = date(self::$dbDateTimeFormat, strtotime($date));
        } else {
            $date = '';
        }

        return $date;
    }

    /**
     * This function will be used to set Date format in Mysql server format.
     *
     * @param date $date
     * @return date $date
     */
    public static function setDbDate($date)
    {
        //Changing date format and return 0 incase og wrong date
        if (strtotime($date)) {
            $date = date(self::$dbDateFormat, strtotime($date));
        } else {
            $date = '';
        }

        return $date;
    }

    /**
     * This function will be used to set Time format in Mysql server format.
     *
     * @param date $date
     * @return date $date
     */
    public static function setDbTime($time)
    {
        //Changing date format and return 0 incase og wrong date
        if (strtotime($time)) {
            $time = date(self::$dbTimeFormat, strtotime($time));
        } else {
            $time = '';
        }

        return $time;
    }

    /*
     * This function is used to convert time in usertimezone
     * @params $fromTime 
     * @params $fromTimezone
     * @params $toTimezone
     * @params $format
     */

    public static function timeZoneConvert($fromTime, $fromTimezone, $toTimezone, $format = 'Y-m-d H:i:s')
    {

        if (isset($fromTimezone) && isset($toTimezone) && !empty($fromTimezone) && !empty($toTimezone)) {
            // create timeZone object , with fromtimeZone
            $from = new \DateTimeZone($fromTimezone);
            // create timeZone object , with totimeZone
            $to = new \DateTimeZone($toTimezone);
            // read give time into ,fromtimeZone
            $orgTime = new \DateTime($fromTime, $from);
            //  create new date time object
            $toTime = new \DateTime($orgTime->format("c"));
            // set target time zone to $toTime ojbect.
            $toTime->setTimezone($to);
            // return reuslt.
            return $toTime->format($format);
        } else {
            return '';
        }
    }

    /**
     * Get time difference between 2 timezone.
     * 
     * @param string $timezoneOne
     * @param string $timezoneTwo
     * @return int $timeDiff
     * @author LY 
     */
    public static function timeDiff($remote_tz, $origin_tz = null)
    {

        if ($origin_tz === null) {
            if (!is_string($origin_tz = date_default_timezone_get())) {
                return false; // A UTC timestamp was returned -- bail out!
            }
        }

        $origin_dtz = new \DateTimeZone($origin_tz);
        $remote_dtz = new \DateTimeZone($remote_tz);
        $origin_dt = new \DateTime("now", $origin_dtz);
        $remote_dt = new \DateTime("now", $remote_dtz);

        //Convert to millisecond
        $offset = ($origin_dtz->getOffset($origin_dt) - $remote_dtz->getOffset($remote_dt)) * 1000;

        return $offset;
    }

    public static function calculateDuration($time)
    {
        $date = Carbon::createFromTimeString($time);
        $start_of_day = $date->copy()->startOfDay();
        return  $date->diffInSeconds($start_of_day);
    }

    public static function convertTimezone($time, $fromTimezone, $toTimezone)
    {
        return Carbon::parse($time, $fromTimezone)->setTimezone($toTimezone)->toDateTimeString();
    }
}
