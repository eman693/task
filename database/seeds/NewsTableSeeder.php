<?php

use Illuminate\Database\Seeder;
use App\Models\News;

class NewsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        // And now, let's create a few articles in our database:
        for ($i = 0; $i < 10; $i++) {
           News::create([
               'date' => $faker->date($format = 'Y-m-d', $max = 'now'),
               'title' => $faker->name,
               'short_desc' => $faker->sentence,
                'text' => $faker->paragraph,
            ]);
        }
    }
}
