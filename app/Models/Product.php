<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 'category', 'price', 'quantity', 'description'
    ];

    public function images()
    {
        return $this->hasMany(Image::class);
    }
}
