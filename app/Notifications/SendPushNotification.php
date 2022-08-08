<?php
namespace App\Notifications;

use Benwilkins\FCM\FcmMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class SendPushNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $title;
    protected $message;
    protected $tag;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($title,$message,$tag=null)
    {
        $this->title = $title;
        $this->message = $message;
        $this->tag = $tag;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['fcm'];
    }

    public function toFcm($notifiable)
    {
        $message = new FcmMessage();
        $message->content([
            'title'        => $this->title,
            'body'         => $this->message,
            'tag'          => $this->tag
        ])->priority(FcmMessage::PRIORITY_HIGH);

        return $message;
    }

}
