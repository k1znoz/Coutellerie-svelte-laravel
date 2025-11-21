<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Knife extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category_id',  // Changé de 'category' vers 'category_id'
        'type_id',      // Changé de 'type' vers 'type_id'
        'material_id',  // Changé de 'material' vers 'material_id'
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

    // Relations avec les nouvelles tables
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class);
    }

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }
}
