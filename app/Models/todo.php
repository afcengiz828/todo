<?php

namespace App\Models;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class todo extends Model
{
    /** @use HasFactory<\Database\Factories\TodoFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ["title", "description", "status", "priority", "due_date", "categories_id"]; 

    protected $casts = [
        'due_date' => 'date:Y-m-d', 
    ];

    public function categories(){
        return $this->belongsTo(Category::class, 'categories_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
