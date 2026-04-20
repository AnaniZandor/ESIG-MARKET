<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Article;
use App\Models\Message;
use App\Models\Review;
use App\Models\Favorite;
use App\Models\Report;
use Illuminate\Notifications\Notifiable;


class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
         // Champs ajoutés pour ton marketplace étudiant
        'avatar',              // photo de profil
        'filiere',             // filière de l'étudiant
        'numero_etudiant',     // numéro étudiant
        'role',                // user ou admin
        'bio',                 // description profil
        'is_active',          // compte actif ou non
    ];
    /*
    |--------------------------------------------------------------------------
    | CHAMPS CACHÉS (SÉCURITÉ API / JSON)
    |--------------------------------------------------------------------------
    | Ces champs ne seront jamais visibles dans les réponses JSON
    */
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
        /*
    |--------------------------------------------------------------------------
    | CASTS (CONVERSION AUTOMATIQUE DES TYPES)
    |--------------------------------------------------------------------------
    | Laravel convertit automatiquement les données en types PHP
    */

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    /*
    |--------------------------------------------------------------------------
    | RELATIONS MARKETPLACE
    |--------------------------------------------------------------------------
    | Ici on connecte User aux autres tables de ton système
    */

    // 1. Un user peut publier plusieurs articles
    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    // 2. Messages envoyés par l'utilisateur
    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    // 3. Messages reçus par l'utilisateur
    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    // 4. Avis laissés par l'utilisateur
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Avis reçus (en tant que vendeur)
public function reviewsReceived()
{
    return $this->hasMany(\App\Models\Review::class, 'reviewed_id');
}

    // 5. Articles favoris
   public function favorites()
{
    return $this->belongsToMany(Article::class, 'favorites')
                ->withTimestamps();
}
/*
   // 6. Notifications reçues
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
*/
    // 7. Signalements envoyés
    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS (FONCTIONS UTILES)
    |--------------------------------------------------------------------------
    | Petites fonctions pratiques pour simplifier ton code
    */

    // Vérifier si l'utilisateur est admin
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    // Vérifier si le compte est actif
    public function isActive()
    {
    return (bool) $this->is_active;
    }

}
