<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | CHAMPS MODIFIABLES
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'user_id',      // utilisateur qui signale
        'article_id',   // article signalé
        'reason',       // raison du signalement
        'message',      // détail du signalement
        'status',       // pending / reviewed / rejected
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    // Le report appartient à un utilisateur
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Le report concerne un article
    public function article()
    {
        return $this->belongsTo(Article::class);
    }
    //
}
