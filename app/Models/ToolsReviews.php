<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ToolsReviews extends Model
{
    //
    use HasFactory;

    protected $table = 'tools_reviews';

    protected $fillable = [
        'tool_id',
        'user_id',
        'review',
        'ratings',
        'status',
    ];

    public function tools ()
    {
        return $this->belongsTo(Tools::class, 'tool_id', 'id');
    }
}
