<?php

namespace Modules\Blog\Entities;

use App\Models\Address;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Blog\Database\factories\BlogFactory;
use Modules\Comment\Entities\Comment;
use Modules\Filemanager\Entities\Filemanager;
use Modules\Magazine\Entities\Magazine;
use Modules\User\Entities\User;

class Blog extends Model
{
    use HasFactory;


    protected $fillable = ["title", "subtitle", "summary", "content", "author_id", "magazine_id", "status", "tags", 'photo_id'];
    protected $casts = [
        "tags" => "array"
    ];

    protected static function newFactory()
    {
        return \Modules\Blog\Database\factories\BlogFactory::new();
    }

    public function magazine()
    {
        return $this->belongsTo(Magazine::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, "author_id");
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')->whereParentId(null);
    }

    public function photo()
    {
        return $this->belongsTo(Filemanager::class, 'photo_id');
    }
}
