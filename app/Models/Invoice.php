<?php

namespace App\Models;
use App\Models\User;
use App\Models\InvoiceProduct;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'total',
        'vat',
        'payable',
        'cus_details',
        'ship_details',
        'tran_id',
        'val_id',
        'delivery_status',
        'payment_status',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->hasMany(InvoiceProduct::class);
    }
}

