<?php

namespace App\Models;

use App\Enums\ActionType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use softDeletes;

    protected $fillable = [
        'title',
        'content',
        'author_id',
        'category_id',
        'likes_count',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function likes()
    {
        return $this->morphMany(Action::class, 'actionable')->where('action_type', ActionType::LIKE);
    }

    public function bookmarks()
    {
        return $this->morphMany(Action::class, 'actionable')->where('action_type', ActionType::BOOKMARK);
    }

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }
}
