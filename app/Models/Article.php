<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Article extends Model
{
    protected $fillable = ['title', 'content', 'imgUrl', 'category_id'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }


    // Define the many-to-many relationship with Tag
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    // Define the many-to-many relationship with Tag
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
    use HasFactory;
}
