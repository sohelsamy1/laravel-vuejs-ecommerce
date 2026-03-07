<?php

namespace App\Models;

use App\Models\Product;
use App\Models\CustomerProfile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'rating',
        'customer_id',
        'product_id',
    ];

    public function customer()
    {
        return $this->belongsTo(CustomerProfile::class, 'customer_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

