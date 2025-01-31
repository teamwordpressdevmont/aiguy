<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ToolComments extends Model
{
    //
    use HasFactory;

    protected $table = 'tool_comments';

    protected $fillable = [
        'tool_id',
        'user_id',
        'parent_comment_id',
        'comment',
        'status',
    ];

    public function tools ()
    {
        return $this->belongsTo(Tools::class, 'tool_id', 'id');
    }
}
