<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
     use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | CHAMPS MODIFIABLES
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'user_id',   // utilisateur concerné
        'type',      // type notification (message, review, system...)
        'title',     // titre court
        'content',   // message complet
        'is_read',   // lu ou non
        'data',      // infos supplémentaires (JSON)
    ];

    /*
    |--------------------------------------------------------------------------
    | CASTS
    |--------------------------------------------------------------------------
    */

    protected $casts = [
        'data' => 'array', // permet de stocker du JSON proprement
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATION
    |--------------------------------------------------------------------------
    */

    // Une notification appartient à un utilisateur
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    //
}
