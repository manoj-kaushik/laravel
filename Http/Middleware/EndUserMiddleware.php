<?php

namespace Modules\Demowebinar\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;

class EndUserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
    
        $encodeUserId = $request->route('userId');
        $request->route()->setParameter('userId', base64_decode($encodeUserId));

        $encodeQuestionId = $request->route('questionId');
        $request->route()->setParameter('questionId', base64_decode($encodeQuestionId));

        $encodeChatId = $request->route('chatId');
        $request->route()->setParameter('chatId', base64_decode($encodeChatId));

        $encodeModeratorId = $request->route('moderatorId');
        $request->route()->setParameter('moderatorId', base64_decode($encodeModeratorId));

        $encodePollSubmissionId = $request->route('pollSubmissionId');
        $request->route()->setParameter('pollSubmissionId', base64_decode($encodePollSubmissionId));

        $webinarId = $request->segment(4);
        $request->server->set('id', $webinarId);

        return $next($request);
    }
}
