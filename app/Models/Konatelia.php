<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Konatelia extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'meno',
        'ulica',
        'psc_mesto',
        'created_at',
    ];

    protected $table = 'konatelia';
}
