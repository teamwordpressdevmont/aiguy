<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AffiliateInteraction extends Model
{
    //
    use HasFactory;

    protected $table = 'affiliate_interactions';

    protected $fillable = [
        'user_id',
        'affiliate_id',
    ];
}
