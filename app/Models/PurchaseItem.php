<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model
{
    protected $table = 'purchase_items';

     protected $fillable = [
        'purchase_id',
        'product_name',
        'price',
        'quantity',
        'line_total',
    ];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }
}
