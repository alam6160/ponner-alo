<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StateheadProfile extends Model
{
    use HasFactory;

    public function statehead()
    {
        return $this->belongsTo(User::class, 'statehead_id')->withDefault();
    }
}
