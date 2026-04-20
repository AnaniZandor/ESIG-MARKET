<?php
namespace App\Notifications;

use Illuminate\Notifications\Notification;
use App\Models\Article;

class FavoriVenduNotification extends Notification
{
    public function __construct(public Article $article) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'message'       => 'Un article dans vos favoris vient d\'être vendu',
            'article_id'    => $this->article->id,
            'article_title' => $this->article->title,
        ];
    }
}