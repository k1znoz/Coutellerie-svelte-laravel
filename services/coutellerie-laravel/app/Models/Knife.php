<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
}
