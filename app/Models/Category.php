<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
   use HasFactory;

   protected $table = 'categories';

   protected $fillable = ["name", "color"]; 

   public function todos()
    {
        // Bir Category modelinin birden fazla Todo modeli vardır.
        return $this->hasMany(Todo::class, "categories_id");
    }
}
