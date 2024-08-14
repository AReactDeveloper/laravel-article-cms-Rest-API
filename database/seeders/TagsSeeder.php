<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TagsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('tags')->insert([
            ['title'=>'html'],
            ['title'=>'css'],
            ['title'=>'js'],
            ['title'=>'php'],
            ['title'=>'python'],
            ['title'=>'django'],
            ['title'=>'laravel']
        ]);
    }
}
