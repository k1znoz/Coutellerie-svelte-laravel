<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Material extends Model
{
    protected $fillable = ['name'];

    public function knives(): BelongsToMany
    {
        return $this->belongsToMany(Knife::class);
    }
}
