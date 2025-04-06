<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use softDeletes;

    protected $fillable = [
        'content',
        'author_id',
        'post_id',
        'likes_count',
        'is_published',
        'published_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function likes()
    {
        return $this->morphMany(Action::class, 'actionable')->where('action_type', 'like');
    }
}
