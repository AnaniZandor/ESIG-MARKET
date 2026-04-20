<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'article_id',
        'body',       // ← était 'content'
        'read_at',    // ← était 'is_read'
    ];

    // Expéditeur du message
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // Destinataire du message
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    // Article lié
    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    // Message lu ?
    public function isRead(): bool
    {
        return $this->read_at !== null;
    }

    // Marquer comme lu
    public function markAsRead(): void
    {
        $this->update(['read_at' => now()]);
    }
}