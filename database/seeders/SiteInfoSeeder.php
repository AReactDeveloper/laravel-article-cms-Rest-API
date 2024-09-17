<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SiteInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('site_infos')->insert([
            'siteName' => 'LeReBlog',
            'siteDescription' => 'Lorem ipsum dolor sit amet consectetur ',
            'siteLogo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/9/90/Mercedes-Logo.svg/2048px-Mercedes-Logo.svg.png',
            'siteFavicon' => 'https://cdn.iconscout.com/icon/free/png-256/free-mercedes-8-202855.png',
            'siteStatus' => 'maintenance',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
