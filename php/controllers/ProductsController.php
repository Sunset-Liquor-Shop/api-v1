<?php
require_once(__DIR__ . '/../config.php');
require_once(__DIR__ . '/../vendor/autoload.php');

use Automattic\WooCommerce\Client;

class ProductsController {
    private $woocommerce;
    
    public function __construct() {
        $this->woocommerce = new Client(
            WC_API_URL,
            WC_CONSUMER_KEY,
            WC_CONSUMER_SECRET,
            [
                'wp_api' => true,
                'version' => 'wc/v3',
            ]
        );
    }
    
    public function get_all_products() {
        $products = $this->woocommerce->get('products');
        
        if (empty($products)) {
            http_response_code(404);
            echo json_encode(array("message" => "No products found."));
        } else {
            http_response_code(200);
            echo json_encode($products);
        }
    }
    
    public function get_product($product_id) {
        $product = $this->woocommerce->get('products/' . $product_id);
        
        if (empty($product)) {
            http_response_code(404);
            echo json_encode(array("message" => "Product not found."));
        } else {
            http_response_code(200);
            echo json_encode($product);
        }
    }
    
    public function create_product($product_data) {
        $result = $this->woocommerce->post('products', $product_data);
        
        if (isset($result->message)) {
            http_response_code(400);
            echo json_encode(array("message" => $result->message));
        } else {
            http_response_code(201);
            echo json_encode($result);
        }
    }
    
    public function update_product($product_id, $product_data) {
        $result = $this->woocommerce->put('products/' . $product_id, $product_data);
        
        if (isset($result->message)) {
            http_response_code(400);
            echo json_encode(array("message" => $result->message));
        } else {
            http_response_code(200);
            echo json_encode($result);
        }
    }
    
    public function delete_product($product_id) {
        $result = $this->woocommerce->delete('products/' . $product_id, ['force' => true]);
        
        if ($result) {
            http_response_code(204);
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "Product not found."));
        }
    }
}
