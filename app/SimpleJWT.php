<?php

namespace App;

use DateTimeImmutable;
use Lcobucci\JWT\Token\Plain;
use Lcobucci\Clock\SystemClock;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Illuminate\Support\Facades\Config;
use Lcobucci\JWT\Validation\Constraint\IssuedBy;
use Lcobucci\JWT\Validation\Constraint\LooseValidAt;
use Lcobucci\JWT\Validation\Constraint\PermittedFor;

class SimpleJWT implements JWT {
    private string $iss;
    private string $aud;

    private Configuration $config;

    function __construct() {
        // inject this in the providers
        $this->iss = Config::get('app.iss');
        $this->aud = Config::get('app.aud');

        $this->config = Configuration::forSymmetricSigner(
            new Sha256(),
            InMemory::base64Encoded('mBC5v1sOKVvbdAzxRSBenu59nfNfhwkedkJVNabosTw=')
        );
    }

    function issueAccessToken(string $uid) {
        $now = new DateTimeImmutable();
        return $this->config->builder()
                    ->issuedBy($this->iss)
                    ->permittedFor($this->aud)
                    ->issuedAt($now)
                    ->canOnlyBeUsedAfter($now)
                    ->expiresAt($now->modify('+1 day'))
                    ->withClaim('uid', $uid)
                    ->getToken($this->config->signer(), $this->config->signingKey());
    }

    function issueRefreshToken() {
        $now = new DateTimeImmutable();
        return $this->config->builder()
                    ->issuedBy($this->iss)
                    ->permittedFor($this->aud)
                    ->issuedAt($now)
                    ->canOnlyBeUsedAfter($now)
                    ->expiresAt($now->modify('+7 days'))
                    ->getToken($this->config->signer(), $this->config->signingKey());;
    }

    function isValid(Plain $token) {
        $constraints = $this->validationConstraints();
        return $this->config->validator()->validate($token, ...$constraints);
    }

    function parseToken(string $tokenString) {
        $token = $this->config->parser()->parse($tokenString);
        if ($token instanceof Plain) {
            return $token;
        }
        return null;
    }

    private function validationConstraints() {
        return [
            new IssuedBy($this->iss),
            new PermittedFor($this->aud),
            new LooseValidAt(SystemClock::fromUTC())
        ];
    }
}