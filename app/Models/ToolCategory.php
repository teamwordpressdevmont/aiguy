<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ToolCategory extends Model
{
    //
    use HasFactory;

    protected $table = 'tool_categories';

    protected $fillable = [
        'name',
        'description',
        'image',
    ];
}
