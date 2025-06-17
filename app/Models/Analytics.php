<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Analytics extends Model
{
    protected $fillable = [
    'eventType',
    'url',
    'path',
    'referrer',
    'timestamp',
    'userId',
    'browserName',
    'browserVersion',
    'os',
    'deviceType',
    'deviceVendor',
    'deviceModel',
    'cpuArchitecture',
    'isArmCpu',
    'isMobile',
    'screenWidth',
    'screenHeight',
    'language',
    'timezone',
    'connectionType',
];
}
