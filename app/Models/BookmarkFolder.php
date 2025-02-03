<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookmarkFolder extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'user_id'];

    // Many-to-many relationship with tools
    public function tools()
    {
        return $this->belongsToMany(Tool::class, 'bookmark_folder_tool', 'folder_id', 'tool_id')->withTimestamps();
    }
}
