<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tool extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];

    public function bookmarkFolders()
    {
        return $this->belongsToMany(BookmarkFolder::class, 'bookmark_folder_tool', 'tool_id', 'folder_id');
    }
}
