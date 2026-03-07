<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductSlider extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'short_des',
        'price',
        'image',
        'product_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    
}

