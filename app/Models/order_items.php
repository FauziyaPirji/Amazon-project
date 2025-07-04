<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class order_items extends Model
{
    use HasFactory;

    protected $casts = [
        'arrivalDate' => 'datetime',
    ];

    protected $fillable = [
        'orderId',
        'productId',
        'itemQty',
        'arrivalDate',
        'deliveryOptionId'
    ];

    public function order()
    {
        return $this->belongsTo(orders::class, 'orderId');
    }

    public function product()
    {
        return $this->belongsTo(product::class, 'productId');
    }
}
