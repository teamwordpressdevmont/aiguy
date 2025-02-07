<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AiTool extends Model
{
    //
    use HasFactory;
    
    protected $table = 'ai_tools';
    
    protected $fillable = [
        'category_id',
        'slug',
        'name',
        'logo',
        'cover',
        'tagline',
        'short_description_heading',
        'short_description',
        'verified_status',
        'payment_status',
        'payment_text',
        'website_link',
        'description_heading',
        'description',
        'key_features',
        'pros',
        'cons',
        'long_description',
        'aitool_filter',
        'added_by',
    ];

    // Define the relationship between AiTool and AIToolsCategory
    public function category()
    {
        return $this->belongsTo(AIToolsCategory::class, 'category_id');
    }
}
