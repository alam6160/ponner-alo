<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'address',
        'pin_code',
        'state_id'
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id')->withDefault();
    }
}
