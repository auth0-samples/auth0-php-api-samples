<?php
namespace App;

use Auth0\SDK\Helpers\JWKFetcher;
use Auth0\SDK\Helpers\Tokens\AsymmetricVerifier;
use Auth0\SDK\Helpers\Tokens\SymmetricVerifier;
use Auth0\SDK\Helpers\Tokens\TokenVerifier;
use Kodus\Cache\FileCache;

class Main
{
    protected $issuer;
    protected $audience;
    protected $secret;
    protected $algorithm;
    protected $token;
    protected $tokenInfo;

    public function __construct($options = [])
    {
        $this->issuer = $options['issuer'] ?? null;
        $this->audience = $options['audience'] ?? null;

        $this->algorithm = $options['algorithm'] ? strtoupper($options['algorithm']) : 'RS256';

        if ($this->algorithm === 'HS256') {
            $this->secret = $options['secret'] ?? '';
        }
    }

    public function setCurrentToken($token)
    {
        $cacheHandler = new FileCache('./cache', 600);
        $jwksUri = $this->issuer . '.well-known/jwks.json';

        if ($this->algorithm === 'HS256') {
            $sigVerifier = new SymmetricVerifier($this->secret);
        } else {
            $jwksFetcher = new JWKFetcher($cacheHandler, ['base_uri' => $jwksUri]);
            $sigVerifier = new AsymmetricVerifier($jwksFetcher);
        }

        // $tokenVerifier = new TokenVerifier($this->issuer, $this->audience, $sigVerifier);
        $tokenVerifier = new TokenVerifier($this->issuer, $this->audience, $sigVerifier);

        $this->tokenInfo = $tokenVerifier->verify($token);
        $this->token = $token;
    }

    public function checkScope($scope)
    {
        if ($this->tokenInfo && isset($this->tokenInfo['scope'])) {
            $scopes = explode(" ", $this->tokenInfo['scope']);

            foreach ($scopes as $s) {
                if ($s === $scope) {
                    return true;
                }
            }
        }

        return false;
    }

    public function publicEndpoint()
    {
        return "Hello from the public endpoint! You don't need to be authenticated to see this.";
    }

    public function privateEndpoint()
    {
        return "Hello from a private endpoint! You need to be authenticated to see this.";
    }

    public function privateScopedEndpoint()
    {
        return "Hello from a private endpoint! You need to be authenticated and have a scope of read:messages to see this.";
    }
}
