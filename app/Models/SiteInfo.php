<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'siteFavicon',
        'siteLogo',
        'siteName',
        'siteDescription',
        'siteUrl',
        'siteAdminEmail',
        'siteLanguage',
        'siteStatus'
    ];
}
