<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;

class Knife extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category_id',  // One-to-many
        'length',
        'description',
        'price',
        'images',
        'available',
    ];

    protected $casts = [
        'available' => 'boolean',
        'images' => 'json',
    ];

    protected $appends = ['title', 'image_urls'];

    // Accesseur pour compatibilité avec le frontend (title = name)
    public function getTitleAttribute(): ?string
    {
        return $this->name;
    }

    // Accesseur pour générer les URLs complètes des images (sans modifier 'images')
    public function getImageUrlsAttribute(): array
    {
        $images = $this->attributes['images'] ?? null;
        
        if (!$images) {
            return [];
        }
        
        $paths = json_decode($images, true) ?? [];
        
        return array_values(array_filter(array_map(function ($path) {
            if (empty($path)) {
                return null;
            }
            
            // Si c'est déjà une URL complète, la retourner
            if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
                return $path;
            }
            
            // Sinon construire l'URL complète
            return config('app.url') . '/storage/' . ltrim($path, '/');
        }, $paths)));
    }

    // Relation one-to-many avec Category
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // Relation many-to-many avec Type
    public function types(): BelongsToMany
    {
        return $this->belongsToMany(Type::class);
    }

    // Relation many-to-many avec Material
    public function materials(): BelongsToMany
    {
        return $this->belongsToMany(Material::class);
    }

    // S'assurer que les relations sont toujours présentes dans le JSON
    public function toArray(): array
    {
        $array = parent::toArray();
        
        // S'assurer que les relations many-to-many sont des tableaux
        if (!isset($array['types']) || !is_array($array['types'])) {
            $array['types'] = [];
        }
        if (!isset($array['materials']) || !is_array($array['materials'])) {
            $array['materials'] = [];
        }
        
        return $array;
    }
}
