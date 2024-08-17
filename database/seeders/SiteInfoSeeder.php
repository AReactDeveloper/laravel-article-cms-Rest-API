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
            'siteStatus' => 'maintenance',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
