<?php

namespace App;

use DateTimeImmutable;
use Lcobucci\JWT\Token\Plain;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Illuminate\Support\Facades\Config;
use Lcobucci\JWT\Validation\Constraint\IdentifiedBy;
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

    function issueToken(string $uid) {
        $now = new DateTimeImmutable();
        return $this->config->builder()
                    ->issuedBy($this->iss)
                    ->permittedFor($this->aud)
                    ->issuedAt($now)
                    ->canOnlyBeUsedAfter($now)
                    ->expiresAt($now->modify('+1 hours'))
                    ->withClaim('uid', $uid)
                    ->getToken($this->config->signer(), $this->config->signingKey());
    }

    function isValid(Plain $token) {
        $constraints = $this->validationConstraints();
        return ! $this->config->validator()->validate($token, ...$constraints) &&
                ! $token->isExpired(new DateTimeImmutable());
    }

    function parseToken(string $tokenString) {
        $tokenString = explode('.', $tokenString);
        $token = $this->config->parser()->parse(
            "{$tokenString[0]}.{$tokenString[1]}.{$tokenString[2]}"
        );
        if ($token instanceof Plain) {
            return $token;
        }
        return null;
    }

    private function validationConstraints() {
        return [
            new IdentifiedBy($this->iss),
            new PermittedFor($this->aud)
        ];
    }
}