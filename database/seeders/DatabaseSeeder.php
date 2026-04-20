<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Catégories
        $categories = [
            ['name' => 'Vêtements',      'slug' => 'vetements',    'icon' => '👗'],
            ['name' => 'Livres & Cours', 'slug' => 'livres',       'icon' => '📚'],
            ['name' => 'Électronique',   'slug' => 'electronique', 'icon' => '💻'],
            ['name' => 'Accessoires',    'slug' => 'accessoires',  'icon' => '👜'],
            ['name' => 'Sport',          'slug' => 'sport',        'icon' => '⚽'],
            ['name' => 'Maison',         'slug' => 'maison',       'icon' => '🏠'],
            ['name' => 'Autre',          'slug' => 'autre',        'icon' => '📦'],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(
                ['slug' => $cat['slug']],
                $cat
            );
        }
    }
}