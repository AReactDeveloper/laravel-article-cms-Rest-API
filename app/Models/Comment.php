<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use App\Models\Article;


class Comment extends Model
{
    protected $fillable = ['article_id','body','author'];

    public function article() : belongsTo{
        return $this->belongsTo(Article::class);
    }

    use HasFactory;
}
