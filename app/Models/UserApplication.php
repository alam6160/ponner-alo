<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserApplication extends Model
{
    use HasFactory, SoftDeletes;

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id')->withDefault();
    }
    public function servicable_state()
    {
        return $this->belongsTo(State::class, 'servicable_state_id')->withDefault();
    }
}
