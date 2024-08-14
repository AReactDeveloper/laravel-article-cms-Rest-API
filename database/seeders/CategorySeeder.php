<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // Insert dummy categories
        DB::table('categories')->insert([
            ['title' => 'Technology'],
            ['title' => 'Health'],
            ['title' => 'Lifestyle'],
            ['title' => 'Education'],
            ['title' => 'Travel'],
        ]);
    }
}
