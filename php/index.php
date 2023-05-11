<?php
// Load dependencies
require_once __DIR__ . '/vendor/autoload.php';

// Load configuration settings
require_once __DIR__ . '/config.php';

// Load helper functions
require_once __DIR__ . '/helpers/ValidationHelper.php';
require_once __DIR__ . '/helpers/JwtHelper.php';

// Load models
require_once __DIR__ . '/models/User.php';
require_once __DIR__ . '/models/Product.php';
require_once __DIR__ . '/models/Order.php';

// Load controllers
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/ProductsController.php';
require_once __DIR__ . '/controllers/OrdersController.php';

use \Firebase\JWT\JWT;

// Define allowed HTTP methods and routes
$routes = [
    'POST' => [
        '/api/register' => 'AuthController@register',
        '/api/login' => 'AuthController@login',
        '/api/orders' => 'OrdersController@createOrder',
    ],
    'GET' => [
        '/api/products' => 'ProductsController@listProducts',
        '/api/products/{id}' => 'ProductsController@getProductById',
    ],
    'PUT' => [
        '/api/orders/{id}' => 'OrdersController@updateOrder',
    ],
];

// Parse the request URL and method
$method = $_SERVER['REQUEST_METHOD'];
$url = $_SERVER['REQUEST_URI'];
$route = strtok($url, '?');
$params = [];

// Check if the URL matches any of the defined routes
if (isset($routes[$method])) {
    foreach ($routes[$method] as $routePattern => $handler) {
        $pattern = str_replace('/', '\/', $routePattern);
        if (preg_match('/^' . $pattern . '$/', $route, $matches)) {
            $handler = explode('@', $handler);
            $controller = new $handler[0]();
            $method = $handler[1];
            $params = $matches;
            break;
        }
    }
}

// If no route is found, return a 404 error
if (!isset($controller) || !method_exists($controller, $method)) {
    http_response_code(404);
    echo json_encode(['error' => 'Route not found']);
    exit;
}

// Check if the endpoint requires authentication
$requiresAuth = in_array($route, ['/api/orders']);

// If authentication is required, validate the JWT token
if ($requiresAuth) {
    $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? null;
    if (!$authHeader || !preg_match('/^Bearer\s+(.*)$/i', $authHeader, $matches)) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }
    $token = $matches[1];
    try {
        $decodedToken = JWT::decode($token, $jwtSecret, ['HS256']);
        $user = new User($decodedToken->sub);
    } catch (Exception $ex) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }
}

// Validate input parameters
$params = array_filter($params);
$params = array_values($params);
$params = array_map('sanitizeInput', $params);

// Call the endpoint method with the input parameters
$response = call_user_func_array([$controller, $method], $params);

// Return the response as JSON
header('Content-Type: application/json');
echo json_encode($response);
