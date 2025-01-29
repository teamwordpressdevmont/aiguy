<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tools extends Model
{
    //
    use HasFactory;

    protected $table = 'tools';

    protected $fillable = [
        'name',
        'description',
        'logo',
        'price',
        'link',
        'avg_rating',
        'total_reviews',
    ];

    public function CategoriesHasTools ()
    {
        return $this->hasMany(CategoriesHasTools::class, 'tool_id', 'id');
    }

    public function PlatformsHasTools ()
    {
        return $this->hasMany(PlatformsHasTools::class, 'tool_id', 'id');
    }

    public function ToolsReviews ()
    {
        return $this->hasMany(ToolsReviews::class, 'tool_id', 'id');
    }
}
