<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ImageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1
        DB::table('image')->insert([
            'type' => 'image',
            'format' => 'jpg',
            'width' => 994,
            'height' => 1138,
            'user_id' => 1,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        // 2
        DB::table('image')->insert([
            'type' => 'image',
            'format' => 'jpg',
            'width' => 1200,
            'height' => 900,
            'user_id' => 1,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        // 3
        DB::table('image')->insert([
            'type' => 'image',
            'format' => 'png',
            'width' => 1200,
            'height' => 1200,
            'user_id' => 1,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        // 4
        DB::table('image')->insert([
            'type' => 'image',
            'format' => 'jpg',
            'width' => 1200,
            'height' => 1200,
            'user_id' => 1,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        // 5
        DB::table('image')->insert([
            'type' => 'youtube',
            'width' => 1280,
            'height' => 720,
            'external_url' => 'https://www.youtube.com/watch?v=KLELsFKC6o4',
            'user_id' => 1,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        // 6
        DB::table('image')->insert([
            'type' => 'vimeo',
            'width' => 1280,
            'height' => 720,
            'external_url' => 'https://vimeo.com/16922821',
            'user_id' => 1,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        // 7
        DB::table('image')->insert([
            'type' => 'image',
            'format' => 'jpg',
            'width' => 1200,
            'height' => 1039,
            'user_id' => 1,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        // 8
        DB::table('image')->insert([
            'type' => 'image',
            'format' => 'jpg',
            'width' => 580,
            'height' => 386,
            'user_id' => 1,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        // 9
        DB::table('image')->insert([
            'type' => 'image',
            'format' => 'jpg',
            'width' => 480,
            'height' => 311,
            'user_id' => 1,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        // 10
        DB::table('image')->insert([
            'type' => 'image',
            'format' => 'jpg',
            'width' => 570,
            'height' => 570,
            'user_id' => 1,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        // 11
        DB::table('image')->insert([
            'type' => 'image',
            'format' => 'jpg',
            'width' => 1200,
            'height' => 900,
            'user_id' => 1,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
    }
}
