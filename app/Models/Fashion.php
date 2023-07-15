<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fashion extends Model
{
    protected $fillable = [
      'title', 'date', 'description', 'images1'
    ];

    protected $table = "Fashion";
}
