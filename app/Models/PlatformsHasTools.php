<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PlatformsHasTools extends Model
{
    //
    use HasFactory;

    protected $table = 'platforms_has_tools';

    protected $fillable = [
        'platform_id',
        'tool_id',
    ];

    public function tools ()
    {
        return $this->belongsTo(Tools::class, 'tool_id', 'id');
    }
}
