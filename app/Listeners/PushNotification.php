<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Tymon\JWTAuth\Providers\Auth\Illuminate\Notifications\DatabaseNotification;
use JPush\Client;

class PushNotification implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    protected $client;
    public function __construct(Client $client)
    {
        //
        $this->client = $client;
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(DatabaseNotification $notification)
    {
        //
        if(app()->environment('local')) {
            return;
        }
        $user = $notification->notifiable;
        if(!$user->registration_id) {
            return;
        }
        //推送消息
        $this->client->push()
            ->setPlatform('all')
            ->addRegistrationId($user->registration_id)
            ->setNotificationAlert(strip_tags($notification->data['reply_content']))
            ->send();
    }
}
