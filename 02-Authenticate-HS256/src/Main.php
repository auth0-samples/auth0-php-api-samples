<?php

namespace App;

class Main {

    protected $token;
    protected $tokenInfo;

    public function setCurrentToken($token) {

        try {
            $this->tokenInfo = \Auth0\SDK\Auth0JWT::decode(
                $token,
                getenv('AUTH0_AUDIENCE'),
                getenv('AUTH0_API_SECRET'),
                ['https://' . getenv('AUTH0_DOMAIN') . '/']
            );
            $this->token = $token;
        }
        catch(\Auth0\SDK\Exception\CoreException $e) {
          throw $e;
        }
    }

    public function publicPing() {
        return array(
            "status" => "ok",
            "message" => "Hello from a public endpoint! You don't need to be authenticated to see this."
        );
    }

    public function privatePing() {
        return array(
            "status" => "ok",
            "message" => "Hello from a private endpoint! You DO need to be authenticated to see this."
        );
    }

    public function adminPing() {
        return array(
            "status" => "ok",
            "message" => "Hello from a private endpoint! You need to be authenticated and have a scope of read:messages to see this."
        );
    }

}