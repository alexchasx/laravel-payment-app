<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'amount',
        'currency',
        'description',
        'message',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'published_at',
        'delete_at',
    ];
}
