<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tools extends Model
{
    use HasFactory;

    protected $fillable = ['tool_name'];

  
    public function comments()
    {
        return $this->hasMany(Comment::class, 'tools_id');
    }
}
