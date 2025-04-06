<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Model
{
    use softDeletes;

    public function fileable()
    {
        return $this->morphTo();
    }
}
