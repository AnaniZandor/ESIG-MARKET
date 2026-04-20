<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
     use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | CHAMPS MODIFIABLES
    |--------------------------------------------------------------------------
    | Ce que Laravel peut remplir automatiquement
    */

    protected $fillable = [
        'name',        // nom de la catégorie (ex: Informatique)
        'slug',        // URL propre (ex: informatique)
        'description', // description optionnelle
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATION MARKETPLACE
    |--------------------------------------------------------------------------
    | Une catégorie peut contenir plusieurs articles
    */

    public function articles()
    {
        return $this->hasMany(Article::class);
    }
    //
}
