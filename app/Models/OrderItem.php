<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    public function order_details()
    {
        return $this->belongsTo(OrderDetails::class, 'order_detail_id');
    }
    public function vendor()
    {
        return $this->belongsTo(User::class, 'agent_id')->withDefault();
    }
}
