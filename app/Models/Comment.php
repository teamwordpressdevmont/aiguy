<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['tools_id', 'content' , 'user_id'];

    public function tool()
    {
        return $this->belongsTo(Tools::class, 'tools_id');
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }
}