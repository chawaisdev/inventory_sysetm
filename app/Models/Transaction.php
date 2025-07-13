<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
    'user_id',
    'amount',
    'purchase_id',
    'payment_method',
    'type',         
    'date',
    'note',
    ];

    public function purchase()
{
    return $this->belongsTo(Purchase::class);
}
}
