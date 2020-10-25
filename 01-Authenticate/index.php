<?php
require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
$dotenv->required(['AUTH0_DOMAIN', 'AUTH0_AUDIENCE'])->notEmpty();

$app = new \App\Main([
    'issuer' => 'https://' . $_ENV['AUTH0_DOMAIN'] . '/',
    'audience' => $_ENV['AUTH0_AUDIENCE'] ?? null,
    'algorithm' => $_ENV['AUTH0_SIGNING_ALGORITHM'] ?? 'RS256',
    'secret' => $_ENV['AUTH0_SIGNING_SECRET'] ?? null,
]);

// Create Router instance
$router = new \Bramus\Router\Router();

// Activate CORS
function sendCorsHeaders()
{
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: Authorization");
    header("Access-Control-Allow-Methods: GET,HEAD,PUT,PATCH,POST,DELETE");
}

function sendResponse($httpStatus, $message, $error = false)
{
    header("HTTP/1.0 $httpStatus");
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['status' => $error ? 'error' : 'ok', 'message' => $message], JSON_PRETTY_PRINT);
    exit();
}

$router->options('/.*', function () {
    sendCorsHeaders();
    exit();
});

sendCorsHeaders();

// Check JWT on private routes
$router->before('GET', '/api/private.*', function () use ($app) {
    $requestHeaders = apache_request_headers();

    if (isset($_GET['token'])) {
        $requestHeaders['authorization'] = $_GET['token'];
    }

    if (!isset($requestHeaders['authorization']) && !isset($requestHeaders['Authorization'])) {
        sendResponse('401 Unauthorized', 'No token provided.');
    }

    $authorizationHeader = isset($requestHeaders['authorization'])
        ? $requestHeaders['authorization']
        : $requestHeaders['Authorization'];

    if ($authorizationHeader == null) {
        sendResponse('401 Unauthorized', 'No authorization header sent.');
    }

    $authorizationHeader = str_replace('Bearer ', '', $authorizationHeader);
    $token = str_replace('Bearer ', '', $authorizationHeader);

    try {
        $app->setCurrentToken($token);
    } catch (\Exception $e) {
        sendResponse('401 Unauthorized', $e->getMessage(), true);
    }
});

$router->get('/api/public', function () use ($app) {
    sendResponse('200 OK', $app->publicEndpoint());
});

$router->get('/api/private', function () use ($app) {
    sendResponse('200 OK', $app->privateEndpoint());
});

// Check for read:messages scope
$router->before('GET', '/api/private-scoped', function () use ($app) {
    if (!$app->checkScope('read:messages')) {
        sendResponse('403 Forbidden', 'Insufficient scope.', true);
    }
});

$router->get('/api/private-scoped', function () use ($app) {
    sendResponse('200 OK', $app->privateScopedEndpoint());
});

$router->set404(function () {
    sendResponse('404 Not Found', 'Page not found.', true);
});

// Run the Router
$router->run();
