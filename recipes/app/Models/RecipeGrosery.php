<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecipeGrosery extends Model
{
    use HasFactory;

    protected $table = 'recipe_groseries';
    protected $fillable = [
        'recipe_id',
        'grosery_store_id',
        'created_at',
        'updated_at',
    ];
}
