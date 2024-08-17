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
                'title' => 'Article Title ' . $index,
                'slug' => 'article-title-' . $index,
                'content' => '<p>This is the content for article ' . $index . '. <strong>HTML content</strong> here.</p>',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
