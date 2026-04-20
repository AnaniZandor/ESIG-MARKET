<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'article_id',
        'path',      // ✅ corrigé — correspond à la colonne en base
        'is_main',
        'order',
    ];

    // Une image appartient à un article
    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    // URL complète de l'image
    public function url(): string
    {
        return asset('storage/' . $this->path);
    }
}