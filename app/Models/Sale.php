<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }
    /**
     * Get the user that owns the sale.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    protected $fillable = [
        'user_id',
        'invoice_no',
        'date',
        'total_amount',
        'paid_amount',
        'due_amount',
        'payment_method',
        'note',
    ];

    protected $casts = [
    'date' => 'date',
    ];

}
