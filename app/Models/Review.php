<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | CHAMPS MODIFIABLES
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'user_id',     // auteur de l'avis
        'article_id',  // article concerné
        'rating',      // note (ex: 1 à 5)
        'comment',     // commentaire texte
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    // L'avis appartient à un utilisateur
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // L'avis appartient à un article
    public function article()
    {
        return $this->belongsTo(Article::class);
    }
    //
}
