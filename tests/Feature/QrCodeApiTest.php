<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class QrCodeApiTest extends TestCase
{
    /**
     * @return void
     */
    public function test_qr_code_api()
    {
        $user = User::find(1);
        if ($user) {
            $token = $user->createToken('authToken')->accessToken;
            $response = $this->json('GET', route('qr-code'), [
                'Accept' => 'application/json',
                'backgroundcolor' => '222,222,255,0',
                'fillcolor' => '222,222,222,0',
                'size' => 500,
                'content' => 'Mvix USA'
            ], ['HTTP_Authorization' => 'Bearer ' . $token]);

            $response->assertStatus(200)->assertJsonStructure(['qr_code']);

            //when any of the required parameter is missing
            $response = $this->json('GET', route('qr-code'), [
                'Accept' => 'application/json',
                'fillcolor' => '222,222,222,0',
                'content' => 'Mvix USA'
            ], ['HTTP_Authorization' => 'Bearer ' . $token]);

            $response->assertStatus(422)->assertJsonStructure(['errors']);

            //wrong brear token or empty bearer token
            $response = $this->json('GET', route('qr-code'), [
                'Accept' => 'application/json',
                'fillcolor' => '222,222,222,0',
                'content' => 'Mvix USA'
            ], ['HTTP_Authorization' => 'Bearer ' . '']);

            $response->assertStatus(422)->assertJsonStructure(['errors']);
        }
    }
}
