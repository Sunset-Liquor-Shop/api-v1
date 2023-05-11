<?php
require_once(__DIR__ . '/../config.php');
require_once(__DIR__ . '/../vendor/autoload.php');

use Firebase\JWT\JWT;

class JwtHelper {
    private static $jwt_secret = JWT_SECRET;

    public static function generate_token($user_id) {
        $payload = array(
            "iss" => JWT_ISSUER,
            "sub" => $user_id,
            "iat" => time(),
            "exp" => time() + (JWT_EXPIRATION_TIME * 60)
        );

        $jwt = JWT::encode($payload, self::$jwt_secret);

        return $jwt;
    }

    public static function validate_token($jwt) {
        try {
            $decoded = JWT::decode($jwt, self::$jwt_secret, array('HS256'));
            $user_id = $decoded->sub;
            return $user_id;
        } catch (\Firebase\JWT\ExpiredException $e) {
            http_response_code(401);
            echo json_encode(array("message" => "Token has expired."));
            exit();
        } catch (Exception $e) {
            http_response_code(401);
            echo json_encode(array("message" => "Invalid token."));
            exit();
        }
    }
}


//This code defines a JwtHelper class that uses the Firebase JWT library to generate and validate JSON Web Tokens (JWTs). The generate_token($user_id) method takes a user ID as input, creates a JWT with an expiration time of JWT_EXPIRATION_TIME minutes, and returns the JWT as a string.

// The validate_token($jwt) method takes a JWT as input, validates the token's signature and expiration time, and returns the user ID encoded in the token. If the token is invalid or has expired, the method returns a JSON error message and sets the HTTP response code to 401 (Unauthorized).

// Note that the $jwt_secret property is set to the value of JWT_SECRET defined in config.php. This value should be a secret string known only to the server, and should not be shared with anyone else.