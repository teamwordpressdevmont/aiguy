<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CategoriesHasTools extends Model
{
    //
    use HasFactory;

    protected $table = 'categories_has_tools';

    protected $fillable = [
        'category_id',
        'tool_id',
    ];

    public function tools ()
    {
        return $this->belongsTo(Tools::class, 'tool_id', 'id');
    }
}
