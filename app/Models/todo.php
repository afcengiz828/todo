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

    protected $fillable = ["title", "description", "status", "priority", "due_date"]; 

    protected $casts = [
        'due_date' => 'date:Y-m-d', 
    ];

    public function category(){
        return $this->belongsTo(Category::class);
    }
}
