<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'img1',
        'img2',
        'img3',
        'img4',
        'des',
        'color',
        'size',
        'product_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

