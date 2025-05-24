<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $numberOfPages = 4;
        foreach (range(1, $numberOfPages) as $index) {
            DB::table('pages')->insert([
                'title' => 'Page ' . $index,
                'slug' => 'page-' . $index,
                'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
