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
                'content' => '
                {
  "time": 1685893123456,
  "blocks": [
    {
      "type": "header",
      "data": {
        "text": "About Us",
        "level": 2
      }
    },
    {
      "type": "paragraph",
      "data": {
        "text": "Welcome to our company! We are dedicated to delivering the best products and services to our customers. Our mission is to innovate and inspire."
      }
    },
    {
      "type": "list",
      "data": {
        "style": "unordered",
        "items": [
          "Founded in 2020",
          "Passionate team of experts",
          "Customer-focused approach",
          "Committed to sustainability"
        ]
      }
    }
  ],
  "version": "2.27.0"
}

                ',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
