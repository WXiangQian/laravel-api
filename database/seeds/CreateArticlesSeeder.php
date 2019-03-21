<?php

use Illuminate\Database\Seeder;

class CreateArticlesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($x=1; $x<=10; $x++) {
            DB::table('articles')->insert([
                'user_id' => rand(1,10),
                'type_id' => rand(1,5),
                'title' => "WXiangQian测试标题{$x}测试标题{$x}测试标题{$x}",
                'content' => "WXiangQian测试内容{$x}测试内容{$x}https://github.com/WXiangQian测试内容{$x}测试内容{$x}https://blog.csdn.net/qq175023117测试内容{$x}测试内容{$x}测试内容{$x}测试内容{$x}测试内容{$x}",
                'created_at' => date('Y-m-d H:i:s', time()),
                'updated_at' => date('Y-m-d H:i:s', time()),
            ]);
        }
    }
}
