<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Knife extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'category',
        'description',
        'images',
        'type',
        'length',
        'material',
        'price',
    ];

    protected $casts = [
        'images' => 'array',
        'price' => 'decimal:2',
    ];

    /**
     * Récupère toutes les catégories uniques des couteaux existants
     */
    public static function getAvailableCategories(): array
    {
        return self::distinct()
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->pluck('category')
            ->sort()
            ->values()
            ->toArray();
    }

    /**
     * Récupère les catégories sous forme de tableau associatif pour Filament
     */
    public static function getCategoriesForSelect(): array
    {
        $categories = self::getAvailableCategories();
        
        // Convertir en format key => value pour Filament Select
        $options = [];
        foreach ($categories as $category) {
            $options[$category] = $category;
        }
        
        // Si aucune catégorie n'existe, retourner un tableau vide
        // L'utilisateur devra créer la première catégorie
        return $options;
    }
}
