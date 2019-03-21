<?php

namespace Tests\Feature;

use Tests\TestCase;

class IndexTest extends TestCase
{
    /**
     * 获取首页数据接口
     * @test
     */
    public function index()
    {
        $response = $this->get('/api/v1/home');

        $response->assertStatus(200);
    }

    /**
     * 查询快递信息接口
     * @test
     */
    public function express()
    {
        $response = $this->post('/api/v1/express',['code' => 669642226043]);

        $response->assertStatus(200);
    }
}
