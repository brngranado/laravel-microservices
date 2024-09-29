<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipes extends Model
{
    use HasFactory;

    protected $table = 'recipes';
    protected $fillable = [
        'name',
        'status_id',
        'created_at',
        'updated_at',
    ];
}
