<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Models\Category;
use App\Models\Brand;
use App\Models\ProductDetails;
use App\Models\ProductSlider;
use App\Models\ProductReview;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'short_des',
        'price',
        'discount',
        'discount_price',
        'image',
        'stock',
        'star',
        'remark',
        'category_id',
        'brand_id',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function details(): HasOne
    {
        return $this->hasOne(ProductDetails::class);
    }

    public function slider(): HasOne
    {
        return $this->hasOne(ProductSlider::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ProductReview::class);
    }
}
