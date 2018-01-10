<?php

namespace App;

use Auth0\SDK\JWTVerifier;

class Main {

  protected $token;
  protected $tokenInfo;

  public function setCurrentToken($token) {

    try {
      $verifier = new JWTVerifier([
        'valid_audiences' => [getenv('AUTH0_AUDIENCE')],
        'client_secret' => getenv('AUTH0_API_SECRET'),
        'authorized_iss' => ['https://' . getenv('AUTH0_DOMAIN') . '/'],
        'secret_base64_encoded' => false
      ]);

      $this->token = $token;
      $this->tokenInfo = $verifier->verifyAndDecode($token);
    }
    catch(\Auth0\SDK\Exception\CoreException $e) {
      throw $e;
    }
  }

  public function checkScope($scope){
    if ($this->tokenInfo){
      $scopes = explode(" ", $this->tokenInfo->scope);
      foreach ($scopes as $s){
        if ($s === $scope)
          return true;
      }
    }

    return false;
  }

  public function publicEndpoint() {
    return array(
      "status" => "ok",
      "message" => "Hello from a public endpoint! You don't need to be authenticated to see this."
    );
  }

  public function privateEndpoint() {
    return array(
      "status" => "ok",
      "message" => "Hello from a private endpoint! You DO need to be authenticated to see this."
    );
  }

  public function privateScopedEndpoint() {
    return array(
      "status" => "ok",
      "message" => "Hello from a private endpoint! You DO need to be authenticated and a scope of read:messages to see this."
    );
  }
}