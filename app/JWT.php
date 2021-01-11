<?php

namespace App;

use Lcobucci\JWT\Token\Plain;

interface JWT {
    /**
     * @param string id for user
     * @return Plain token
     */
    function issueToken(string $id);

    /**
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