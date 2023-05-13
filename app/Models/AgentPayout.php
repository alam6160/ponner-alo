<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AgentPayout extends Model
{
    use HasFactory, SoftDeletes;

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id')->withDefault();
    }
}
