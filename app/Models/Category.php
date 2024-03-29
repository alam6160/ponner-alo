<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id')->withDefault();
    }
    public function children()
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }
}
