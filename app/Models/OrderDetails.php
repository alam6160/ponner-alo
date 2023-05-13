<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetails extends Model
{
    use HasFactory;

    public function order_items()
    {
        return $this->hasMany(OrderItem::class , 'order_detail_id');
    }
    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault();
    }
}
