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
        'images' => 'json', // Stocker comme JSON
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
        // Le cast 'json' a déjà décodé la valeur
        // Si c'est déjà un tableau, l'utiliser directement
        if (is_array($value)) {
            $images = $value;
        } else {
            // Sinon, essayer de décoder depuis la BDD
            $rawValue = $this->attributes['images'] ?? null;
            if (!$rawValue) {
                return [];
            }
            $images = json_decode($rawValue, true) ?? [];
        }
        
        // Si le tableau est vide, retourner vide
        if (empty($images)) {
            return [];
        }
        
        // Convertir les chemins relatifs en URLs absolues
        return array_map(function ($image) {
            // Si l'image est déjà une URL complète, la retourner telle quelle
            if (is_string($image) && (str_starts_with($image, 'http://') || str_starts_with($image, 'https://'))) {
                return $image;
            }
            // Générer l'URL publique avec le bon préfixe
            return config('app.url') . '/storage/' . $image;
        }, $images);
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
