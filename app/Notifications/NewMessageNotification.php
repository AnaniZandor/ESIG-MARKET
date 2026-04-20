<?php
namespace App\Notifications;

use Illuminate\Notifications\Notification;
use App\Models\Message;

class NewMessageNotification extends Notification
{
    public function __construct(public Message $message) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'message'       => 'Vous avez reçu un nouveau message',
            'sender_name'   => $this->message->sender->name,
            'article_id'    => $this->message->article_id,
            'article_title' => $this->message->article->title,
            'message_id'    => $this->message->id,
        ];
    }
}