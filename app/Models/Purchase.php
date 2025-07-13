<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    
     protected $fillable = [
        'user_id',
        'brand_id',
        'invoice_no',
        'total_amount',
        'paid_amount',
        'due_amount',
        'payment_method',
        'date',
        'note',
    ];

    protected $casts = [
    'date' => 'date',
    ];


    public function items()
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
    
    public function returns()
    {
        return $this->hasMany(PurchaseReturn::class);
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }
}
