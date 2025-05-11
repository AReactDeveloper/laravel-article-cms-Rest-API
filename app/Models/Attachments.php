<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachments extends Model
{


    use HasFactory;

    protected $fillable = [
        'url',
        'path',
        'filename',
        'mime_type',
        'size',
        'article_id',
    ];
}
