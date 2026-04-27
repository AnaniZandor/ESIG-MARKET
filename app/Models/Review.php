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
        'reviewer_id',  // auteur de l'avis (celui qui note)
        'reviewed_id',  // vendeur noté (celui qui reçoit la note)
        'article_id',   // article concerné
        'rating',       // note (ex: 1 à 5)
        'comment',      // commentaire texte
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    // L'auteur de l'avis (celui qui note)
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    // Le vendeur noté (celui qui reçoit la note)
    public function reviewed()
    {
        return $this->belongsTo(User::class, 'reviewed_id');
    }

    // L'article concerné
    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}