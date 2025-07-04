<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\product;
use App\Models\User;
use App\Models\deliveryOption;

class cart extends Model
{
    use HasFactory;

    protected $table = 'cart';

    protected $fillable = [
        'productId',
        'userId',
        'itemQty',
        'deliveryOptionId'
    ];

    public function product()
    {
        return $this->belongsTo(product::class, 'productId');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }

    public function deliveryOption()
    {
        return $this->belongsTo(deliveryOption::class, 'deliveryOptionId');
    }
}
