<?php

namespace Tests\Unit;

use App\SimpleJWT;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * Assert if token has valid iss, aud, and exp
     *
     * @return void
     */
    public function testTokenValidity()
    {
        $jwt = new SimpleJWT();
        $token = $jwt->issueToken(1);
        $is_valid = $jwt->isValid($token);

        $this->assertTrue($is_valid);
    }

    /**
     * Test the parser function
     *
     * @return void
     */
    public function testTokenParser() {
        $jwt = new SimpleJWT();
        $token = $jwt->parseToken('eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczovL3Rlc3QuY29vbGNoYXQuY29tIiwiYXVkIjoiaHR0cHM6Ly90ZXN0LWF1dGguY29vbGNoYXQuY29tIiwiaWF0IjoiMTYxMDM1MTgyOC45ODMxNDAiLCJuYmYiOiIxNjEwMzUxODI4Ljk4MzE0MCIsImV4cCI6IjE2MTAzNTU0MjguOTgzMTQwIiwidWlkIjoiMSJ9.Ec0dm8VTzdA9yZe2QoatnXm7Xy8AOzvwdB-jJssvrlE');
        
       $this->assertNotNull($token); 
    }

    /**
     * Assert for expired token
     *
     * @return void
     */
    public function testTokenExpirationValidation() {
        $jwt = new SimpleJWT();
        $token = $jwt->parseToken('eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczovL3Rlc3QuY29vbGNoYXQuY29tIiwiYXVkIjoiaHR0cHM6Ly90ZXN0LWF1dGguY29vbGNoYXQuY29tIiwiaWF0Ijo5NDY2ODQ4MDAsIm5iZiI6OTQ2Njg0ODAwLCJleHAiOjk0NjY4ODQwMCwidWlkIjoiMSJ9.x-bsaxZyF-0JUjnKtIHQ4BNfQrmiaYLP6s22CIfR3hs');
        $is_valid = $jwt->isValid($token);

        $this->assertFalse($is_valid);
    }
}
