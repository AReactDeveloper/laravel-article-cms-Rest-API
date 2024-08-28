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
                'content' => 'hello world',
                'imgUrl' => 'https://via.placeholder.com/150',
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
