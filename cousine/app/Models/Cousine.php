<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cousine extends Model
{
    use HasFactory;
    protected $table = 'cousines';
    protected $fillable = [
        'order_number',
        'status_id',
        'recipe_id',
        'created_at',
        'updated_at',
    ];
}
