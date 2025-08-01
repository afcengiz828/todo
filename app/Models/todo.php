<?php

namespace App\Models;

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
        'due_date' => 'date:Y-m-d', // 'date' olarak cast etmek Carbon objesi yapar
        // Veya sadece tarih bekliyorsanız:
        // 'due_date' => 'date', 
        // Veya belirli bir formatla cast etmek istiyorsanız (okuma ve yazma için):
        // 'due_date' => 'datetime:Y-m-d', 
    ];
}
