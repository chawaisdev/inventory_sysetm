<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'brand_id',
        'name',
        'purchase_price',
        'sale_price',
        'stock',
        'unit',
    ];

}
