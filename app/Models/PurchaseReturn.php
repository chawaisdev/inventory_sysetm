<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseReturn extends Model
{
     protected $fillable = [
        'purchase_id',
        'product_name',
        'price',
        'quantity',
        'return_amount',
        'return_date',
    ];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }
}
