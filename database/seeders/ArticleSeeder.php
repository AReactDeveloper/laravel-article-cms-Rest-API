<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // Number of dummy articles to create
        $numberOfArticles = 20;

        // Get all tag IDs
        $tagIds = DB::table('tags')->pluck('id')->toArray();

        // Get existing category IDs
        $categoryIds = DB::table('categories')->pluck('id')->toArray();

        // Generate dummy data
        foreach (range(1, $numberOfArticles) as $index) {
            $articleId = DB::table('articles')->insert([
                'title' => 'Article Title ' . $index,
                'slug' => 'article-title-' . $index,
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
                'imgUrl' => 'https://picsum.photos/id/' . $index + rand(0, 100) . '/150/150',
                'category_id' => $categoryIds[array_rand($categoryIds)],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Attach random tags to the article
        $articleTags = array_rand($tagIds, 3); // Randomly pick 3 tags
        foreach ($articleTags as $tagId) {
            DB::table('article_tag')->insert([
                'article_id' => $articleId,
                'tag_id' => $tagIds[$tagId],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
