<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_code',
        'product_name',
        'category_id',
        'product_stock',
        'product_image',
        'location',
        'category_date',
    ];

    protected $casts = ['category_date' => 'date'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
