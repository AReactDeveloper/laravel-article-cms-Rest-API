<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Article extends Model
{
    protected $fillable = ['title', 'content', 'imgUrl', 'category_id'];

    public function categories(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }


    // Define the many-to-many relationship with Tag
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    use HasFactory;
}
