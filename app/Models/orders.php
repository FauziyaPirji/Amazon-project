<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class orders extends Model
{
    use HasFactory;

    protected $casts = [
        'placeDate' => 'datetime',
    ];

    protected $fillable = [
        'userId',
        'placeDate',
        'total_price'
    ];

    public function orderitems()
    {
        return $this->hasMany(order_items::class, 'orderId');
    }
}
