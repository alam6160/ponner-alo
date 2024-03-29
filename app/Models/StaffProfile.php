<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffProfile extends Model
{
    use HasFactory;

    public function staff()
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault();
    }
}
