<?php

namespace App;

use Lcobucci\JWT\Token\Plain;

interface JWT {
    /**
     * Generate access token
     * 
     * @param string id for user
     * @return Plain token
     */
    function issueAccessToken(string $id);

    /**
     * Generate refresh token to request new access token
     * 
     * @return Plain token
     * @return void
     */
    function issueRefreshToken();

    /**
     * Verify if token is valid
     * 
     * @param Plain token
     * @return boolean <code>true</code> valid token;
     *                 <code>false</code> otherwise.
     */
    function isValid(Plain $token);

    /**
     * Parse string token
     *
     * @param string $token
     * @return Plain
     */
    function parseToken(string $token);
}