<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tools extends Model
{
    //
    use HasFactory;

    protected $table = 'tools';

    protected $fillable = [
        'name',
        'description',
        'logo',
        'price',
        'link',
    ];
}
