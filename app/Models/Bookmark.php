<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bookmark extends Model
{
    protected $fillable = ['user_id'];

    public function likable()
    {
        return $this->morphTo();
    }
}
