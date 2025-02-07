<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Academy extends Model
{
    //
    use HasFactory;
    
    protected $table = 'academies';
    
    protected $fillable = [
        'name',
        'description',
        'academy_logo',
        'academy_image',
        'pricing',
        'affiliate_link' => '#',
    ];

}
