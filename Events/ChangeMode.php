<?php

namespace Modules\Demowebinar\Events;

use Modules\Demowebinar\Entities\Chat;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Demowebinar\Transformers\Chat\ChatResource;

class ChangeMode implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $queue = 'demowebinar_events';

    public $comment;

    public function __construct(ChatResource $comment)
    {
        $this->comment = $comment;
    }

    public function broadcastOn()
    {
        $encodedWebinarId = base64_encode($this->comment->webinar_id);
        $encodedUserId = base64_encode($this->comment->user_id);

        return new PrivateChannel("webinar.user");


        return $sendTo;
    }

    public function broadcastWith()
    {
        return (array) new ChatResource($this->comment);
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return "ChangeMode";
    }
}
