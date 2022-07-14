<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MailNotification extends Notification
{
    
    use Queueable;
    private $arr;
 
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($arr)
    {
        $this->arr = $arr;
    }
 
    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }
 
    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)                    
            ->greeting($this->arr['title'])
            ->from('mohamadtumeh123123@gmail.com')
            ->subject('Khalea-Alena')
            ->line($this->arr['body'])
            ->line($this->arr['code'])
            ->salutation($this->arr['lastLine']);
    }
 
    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
         
        ];
}

}
