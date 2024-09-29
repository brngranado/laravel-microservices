<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroceryStore extends Model
{
    use HasFactory;

    protected $table = 'grocery_stores';

    protected $fillable = [
        'ingredient',
        'quantity',
        'status_id',
        'code',
        'created_at',
        'updated_at'
    ];
}
