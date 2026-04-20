<?php
namespace App\Notifications;

use Illuminate\Notifications\Notification;
use App\Models\Article;

class ArticleSuspenduNotification extends Notification
{
    public function __construct(public Article $article) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'message'       => 'Votre annonce a été suspendue par un administrateur',
            'article_id'    => $this->article->id,
            'article_title' => $this->article->title,
        ];
    }
}