<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $numberOfArticles = 20;

        $tagIds = DB::table('tags')->pluck('id')->toArray();
        $categoryIds = DB::table('categories')->pluck('id')->toArray();

        for ($i = 1; $i <= $numberOfArticles; $i++) {
            // Generate a unique slug
            $baseSlug = Str::slug("Article Title $i");
            $slug = $baseSlug;
            $counter = 1;

            while (DB::table('articles')->where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $counter++;
            }

            // Insert article
            $articleId = DB::table('articles')->insertGetId([
                'title' => "Article Title $i",
                'slug' => $slug,
                'content' => '{
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
        "text": "Lorem ipsum dolor sit amet, consectetur adipisicing elit..."
      }
    }
  ],
  "version": "2.22.2"
}',
                'imgUrl' => 'https://picsum.photos/id/' . ($i + rand(0, 100)) . '/150/150',
                'category_id' => $categoryIds[array_rand($categoryIds)],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Attach 3 random tags to the article
            $randomTags = array_rand($tagIds, min(3, count($tagIds)));
            foreach ((array) $randomTags as $tagIndex) {
                DB::table('article_tag')->insert([
                    'article_id' => $articleId,
                    'tag_id' => $tagIds[$tagIndex],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
