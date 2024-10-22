<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Companies extends Model
{
    use HasFactory;

    protected $table = 'finstat_data';

    protected $fillable = [
        'url',
        'address',
        'creation_date',
    ];

    public $timestamps = false;
}
