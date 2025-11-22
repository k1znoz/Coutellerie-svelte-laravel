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
        'images' => 'array',
        'available' => 'boolean',
    ];

    protected $appends = ['title'];

    // Accesseur pour compatibilité avec le frontend (title = name)
    public function getTitleAttribute(): ?string
    {
        return $this->name;
    }

    // Accesseur pour convertir les chemins d'images en URLs complètes
    public function getImagesAttribute($value): array
    {
        $images = json_decode($value, true) ?? [];
        
        return array_map(function ($image) {
            // Si l'image est déjà une URL complète, la retourner telle quelle
            if (str_starts_with($image, 'http://') || str_starts_with($image, 'https://')) {
                return $image;
            }
            // Générer l'URL publique avec le bon préfixe
            return config('app.url') . '/storage/' . $image;
        }, $images);
    }

    // Mutateur pour stocker les images
    public function setImagesAttribute($value): void
    {
        $this->attributes['images'] = json_encode($value);
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
