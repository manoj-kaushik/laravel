<?php

namespace Modules\Demowebinar\Helpers;

use Config;
use Mail;
use Storage;
use Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\ErrorHandler\Exception\FlattenException;

class ExceptionHelper
{

    public static $mailTemplate = 'demowebinar::emails';

    /**
     * Send the Mails to the User with  data
     *
     * @param array $params
     * @param string $blade
     * @param array $attachments
     * @params string $template
     */
    public static function sendMail($params, $blade, $attachmentPath = array(), $template = '')
    {
        if (empty($template)) {
            $template = self::$mailTemplate;
        }

        //Mail template
        $templateBlade = $template . '.' . $blade;

        //Send mail to
        $recievers = $params['to'];

        //Email subject
        $subject = $params['subject'];

        //Mail related dynamic data
        $mailInfo = $params['data'];

        //Mail sent from
        $fromEmail = isset($params['from']) ? $params['from'] : Config::get('mail.from.address');
        $fromName = isset($params['from_name']) ? $params['from_name'] : Config::get('mail.from.address');

        try {
            //Send mail
            Mail::send($templateBlade, ['mailInfo' => $mailInfo], function ($message) use ($fromEmail, $fromName, $recievers, $subject, $attachmentPath) {
                $message->from($fromEmail, $fromName);
                $message->to($recievers);
                $message->subject($subject);

                if (!empty($attachmentPath)) {
                    $message->attach($attachmentPath);
                }
            });

            return true;
        } catch (\Exception $exception) {
            return false;
        }
    }

    /**
     * Send Error mail to admin.
     *
     * @param object $exception
     * @return boolean false
     * @author KS
     */
    public static function errorMail($exception)
    {
        $params = array();


        //Send error email
        Self::sendMail($params, 'error', $path);
    }

    /**
     * Log Error Details
     *
     * @param object $exception
     * @return integer $statusCode
     */
    public static function errorLog($exception)
    {
        //Logging error
        Log::error("Error: " . $exception->getMessage() . " | File: " . $exception->getFile() . " | File: " . $exception->getLine());

        //Send error mail
        // if(!($exception instanceof ModelNotFoundException))
        // {
        //     Self::errorMail($exception);
        // }

        //status code
        $statusCode = FlattenException::create($exception)->getStatusCode();
        return $statusCode;
    }
}
