<?php

use Illuminate\Database\Seeder;

class CreateArticleTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $articleTypes = [
            '0' => 'php',
            '1' => 'html5',
            '2' => 'java',
            '3' => 'mysql',
            '4' => 'python',
            '5' => 'linux',
        ];
        foreach ($articleTypes as $articleType) {
            DB::table('article_types')->insert([
                'name' => $articleType,
                'pid' => 0,
                'sort' => 0,
                'created_at' => date('Y-m-d H:i:s', time()),
                'updated_at' => date('Y-m-d H:i:s', time()),
            ]);
        }
    }
}
