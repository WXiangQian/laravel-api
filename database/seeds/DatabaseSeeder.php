<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call(CreateUsersSeeder::class);
        $this->call(CreateArticleTypesSeeder::class);
        $this->call(CreateArticlesSeeder::class);
    }
}
