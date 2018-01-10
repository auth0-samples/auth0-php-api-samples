<?php
  // In case one is using PHP 5.4's built-in server
  $filename = __DIR__ . preg_replace('#(\?.*)$#', '', $_SERVER['REQUEST_URI']);
  if (php_sapi_name() === 'cli-server' && is_file($filename)) {
    return false;
  }

  if( !function_exists('apache_request_headers') ) {

    function apache_request_headers() {
      $arh = array();
      $rx_http = '/\AHTTP_/';
      foreach($_SERVER as $key => $val) {
        if( preg_match($rx_http, $key) ) {
          $arh_key = preg_replace($rx_http, '', $key);
          $rx_matches = array();
          // do some nasty string manipulations to restore the original letter case
          // this should work in most cases
          $rx_matches = explode('_', $arh_key);
          if( count($rx_matches) > 0 and strlen($arh_key) > 2 ) {
            foreach($rx_matches as $ak_key => $ak_val) $rx_matches[$ak_key] = ucfirst($ak_val);
            $arh_key = implode('-', $rx_matches);
          }
          $arh[ucfirst(strtolower($arh_key))] = $val;
        }
      }
      return( $arh );
    }
  }


  // Require composer autoloader
  require __DIR__ . '/vendor/autoload.php';

  // Read .env
  try {
    $dotenv = new Dotenv\Dotenv(__DIR__);
    $dotenv->load();
  } catch(InvalidArgumentException $ex) {
    // Ignore if no dotenv
  }

  $app = new \App\Main();

  // Create Router instance
  $router = new \Bramus\Router\Router();

  // Activate CORS
  function sendCorsHeaders() {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: Authorization");
    header("Access-Control-Allow-Methods: GET,HEAD,PUT,PATCH,POST,DELETE");
  }

  $router->options('/.*', function() {
      sendCorsHeaders();
  });

  sendCorsHeaders();

  // Check JWT on private routes
  $router->before('GET', '/api/private.*', function() use ($app) {

    $requestHeaders = apache_request_headers();

    if (!isset($requestHeaders['authorization']) && !isset($requestHeaders['Authorization'])) {
      header('HTTP/1.0 401 Unauthorized');
      header('Content-Type: application/json; charset=utf-8');
      echo json_encode(array("message" => "No token provided."));
      exit();
    }

    $authorizationHeader = isset($requestHeaders['authorization']) ? $requestHeaders['authorization'] : $requestHeaders['Authorization'];

    if ($authorizationHeader == null) {
      header('HTTP/1.0 401 Unauthorized');
      header('Content-Type: application/json; charset=utf-8');
      echo json_encode(array("message" => "No authorization header sent."));
      exit();
    }

    $authorizationHeader = str_replace('bearer ', '', $authorizationHeader);
    $token = str_replace('Bearer ', '', $authorizationHeader);

    try {
      $app->setCurrentToken($token);
    }
    catch(\Auth0\SDK\Exception\CoreException $e) {
      header('HTTP/1.0 401 Unauthorized');
      header('Content-Type: application/json; charset=utf-8');
      echo json_encode(array("message" => $e->getMessage()));
      exit();
    }

  });

  // Check for read:messages scope
  $router->before('GET', '/api/private-scoped', function() use ($app) {
    if (!$app->checkScope('read:messages')){
      header('HTTP/1.0 403 forbidden');
      header('Content-Type: application/json; charset=utf-8');
      echo json_encode(array("message" => "Insufficient scope."));
      exit();
    }
  });

  $router->get('/api/public', function() use ($app){
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($app->publicEndpoint());
  });

  $router->get('/api/private', function() use ($app){
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($app->privateEndpoint());
  });

  $router->get('/api/private-scoped', function() use ($app){
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($app->privateScopedEndpoint());
  });

  $router->set404(function() {
    header('HTTP/1.1 404 Not Found');
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(array("message" => "Page not found."));
  });

  // Run the Router
  $router->run();
