<?php
require_once '../vendor/autoload.php';
require_once '../config.php';
require_once '../models/User.php';

use \Firebase\JWT\JWT;

class AuthController {
  
  public function register() {
    $data = json_decode(file_get_contents('php://input'));
    
    if(empty($data->email) || empty($data->password)) {
      http_response_code(400);
      echo json_encode(array('message' => 'Email and Password are required.'));
      return;
    }
    
    $user = new User();
    $user->email = $data->email;
    $user->password = password_hash($data->password, PASSWORD_DEFAULT);
    
    if(!$user->create()) {
      http_response_code(500);
      echo json_encode(array('message' => 'Error creating user.'));
      return;
    }
    
    http_response_code(201);
    echo json_encode(array('message' => 'User created successfully.'));
  }
  
  public function login() {
    $data = json_decode(file_get_contents('php://input'));
    
    if(empty($data->email) || empty($data->password)) {
      http_response_code(400);
      echo json_encode(array('message' => 'Email and Password are required.'));
      return;
    }
    
    $user = User::findByEmail($data->email);
    
    if(!$user) {
      http_response_code(401);
      echo json_encode(array('message' => 'Invalid credentials.'));
      return;
    }
    
    if(!password_verify($data->password, $user->password)) {
      http_response_code(401);
      echo json_encode(array('message' => 'Invalid credentials.'));
      return;
    }
    
    $issuedAt = time();
    $expirationTime = $issuedAt + JWT_EXPIRATION_TIME;
    
    $payload = array(
        'email' => $user->email,
        'exp' => $expirationTime,
        'iat' => $issuedAt
    );
    
    $jwt = JWT::encode($payload, JWT_SECRET);
    
    http_response_code(200);
    echo json_encode(array('jwt' => $jwt));
  }
  
  public function authorize($jwt) {
    if(!$jwt) {
      http_response_code(401);
      echo json_encode(array('message' => 'Missing authorization token.'));
      return false;
    }
    
    try {
      $decoded = JWT::decode($jwt, JWT_SECRET, array('HS256'));
      return true;
    } catch(Exception $e) {
      http_response_code(401);
      echo json_encode(array('message' => 'Invalid authorization token.'));
      return false;
    }
  }
}
// please note that this code assumes that you have already defined constants like DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, JWT_SECRET, and JWT_EXPIRATION_TIME in your config.php file.
// You would also need to install the firebase/php-jwt library for JWT token generation.