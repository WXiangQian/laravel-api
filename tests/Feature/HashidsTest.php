<?php

namespace Tests\Feature;

use Tests\TestCase;

class HashidsTest extends TestCase
{
    /**
     * hashids加密接口
     * @test
     */
    public function encode()
    {
        $response = $this->post('/api/v1/hash_ids/encode',['id' => 1]);

        $response->assertStatus(200);
    }

    /**
     * hashids解密接口
     * @test
     */
    public function decode()
    {
        $response = $this->post('/api/v1/hash_ids/decode',['hash_id' => 'VBX76D']);


        $response->assertStatus(200);
    }
}
