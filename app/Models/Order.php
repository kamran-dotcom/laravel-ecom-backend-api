<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'city',
        'state',
        'zipcode',
        'address',
        'payment_id',
        'payment_mode',
        'tracking_number',
        'status',
        'remarks',
    ];

    public function orderitems()
    {
        return $this->hasMany(orderItem::class,'order_id','id');
    }
}
