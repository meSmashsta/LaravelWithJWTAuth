<?php

namespace Tests\Unit;

use App\JWT;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    private JWT $jwt;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->jwt = $this->app->make('App\JWT');
    }

    /**
     * Assert if token has valid iss, aud, and exp
     *
     * @return void
     */
    public function testAccessTokenValidity()
    {
        $token = $this->jwt->issueAccessToken(1);
        $is_valid = $this->jwt->isValid($token);

        $this->assertTrue($is_valid);
    }

    public function testRefreshTokenValidity() {
        $refresh_token = $this->jwt->issueRefreshToken();
        $is_valid = $this->jwt->isValid($refresh_token);

        $this->assertTrue($is_valid);
    }

    /**
     * Test the parser function
     *
     * @return void
     */
    public function testTokenParser() {
        $token = $this->jwt->parseToken('eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczovL3Rlc3QuY29vbGNoYXQuY29tIiwiYXVkIjoiaHR0cHM6Ly90ZXN0LWF1dGguY29vbGNoYXQuY29tIiwiaWF0IjoiMTYxMDM1MTgyOC45ODMxNDAiLCJuYmYiOiIxNjEwMzUxODI4Ljk4MzE0MCIsImV4cCI6IjE2MTAzNTU0MjguOTgzMTQwIiwidWlkIjoiMSJ9.Ec0dm8VTzdA9yZe2QoatnXm7Xy8AOzvwdB-jJssvrlE');
        
       $this->assertNotNull($token); 
    }

    /**
     * Assert for expired token
     *
     * @return void
     */
    public function testTokenExpirationValidation() {
        $token = $this->jwt->parseToken('eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczovL3Rlc3QuY29vbGNoYXQuY29tIiwiYXVkIjoiaHR0cHM6Ly90ZXN0LWF1dGguY29vbGNoYXQuY29tIiwiaWF0Ijo5NDY2ODQ4MDAsIm5iZiI6OTQ2Njg0ODAwLCJleHAiOjk0NjY4ODQwMCwidWlkIjoiMSJ9.x-bsaxZyF-0JUjnKtIHQ4BNfQrmiaYLP6s22CIfR3hs');
        $is_valid = $this->jwt->isValid($token);

        $this->assertFalse($is_valid);
    }
}
