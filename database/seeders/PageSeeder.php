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
  "time": 1633046456753,
  "blocks": [
    {
      "type": "header",
      "data": {
        "text": "Welcome to Editor.js",
        "level": 1
      }
    },
    {
      "type": "paragraph",
      "data": {
        "text": "Lorem ipsum dolor sit amet, consectetur adipisicing elit. Non voluptate iure sunt ducimus dicta enim deleniti atque sapiente consequuntur minima id laboriosam, magnam facere vitae.."
      }
    },
    {
      "type": "list",
      "data": {
        "style": "unordered",
        "items": [
          "Clean JSON output",
          "Block-based structure",
          "Easy to integrate"
        ]
      }
    },
    {
      "type": "image",
      "data": {
        "file": {
          "url": "https://picsum.photos/200/300"
        },
        "caption": "An example image",
        "stretched": false,
        "withBorder": true,
        "withBackground": false
      }
    }
  ],
  "version": "2.22.2"
}',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
