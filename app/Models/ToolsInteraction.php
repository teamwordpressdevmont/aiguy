<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ToolsInteraction extends Model
{
    //
    use HasFactory;

    protected $table = 'tools_interactions';

    protected $fillable = [
        'tool_id',
        'user_id',
    ];

    public function tool() {
        return $this->belongsTo(Tools::class, 'tool_id', 'id');
    }
}
