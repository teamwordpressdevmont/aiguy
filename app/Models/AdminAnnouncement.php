<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminAnnouncement extends Model
{
    use HasFactory;
    
    protected $fillable = ['announcement_name', 'announcement_description'];

    public function tracks()
    {
        return $this->hasMany(TrackAnnouncement::class, 'announcement_id');
    }
}