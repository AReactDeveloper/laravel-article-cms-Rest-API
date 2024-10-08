<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Article;


class Tag extends Model
{
    protected $fillable = ['title'];

    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class);
    }

    use HasFactory;
}
