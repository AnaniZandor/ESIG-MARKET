<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | CHAMPS MODIFIABLES
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'user_id',     // utilisateur qui ajoute en favori
        'article_id',  // article mis en favori
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    // Le favori appartient à un utilisateur
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Le favori concerne un article
    public function article()
    {
        return $this->belongsTo(Article::class);
    }
    //
}
