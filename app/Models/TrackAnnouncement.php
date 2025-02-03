<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrackAnnouncement extends Model
{
    use HasFactory;
    
    protected $fillable = ['announcement_id', 'user_id'];

    public function announcement()
    {
        return $this->belongsTo(AdminAnnouncement::class, 'announcement_id');
    }
}