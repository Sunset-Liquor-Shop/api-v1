<?php
require_once(__DIR__ . '/../config.php');
require_once(__DIR__ . '/../vendor/autoload.php');

use Automattic\WooCommerce\Client;

class OrdersController {
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
    
    public function get_all_orders() {
        $orders = $this->woocommerce->get('orders');
        
        if (empty($orders)) {
            http_response_code(404);
            echo json_encode(array("message" => "No orders found."));
        } else {
            http_response_code(200);
            echo json_encode($orders);
        }
    }
    
    public function get_order($order_id) {
        $order = $this->woocommerce->get('orders/' . $order_id);
        
        if (empty($order)) {
            http_response_code(404);
            echo json_encode(array("message" => "Order not found."));
        } else {
            http_response_code(200);
            echo json_encode($order);
        }
    }
    
    public function create_order($order_data) {
        $result = $this->woocommerce->post('orders', $order_data);
        
        if (isset($result->message)) {
            http_response_code(400);
            echo json_encode(array("message" => $result->message));
        } else {
            http_response_code(201);
            echo json_encode($result);
        }
    }
    
    public function update_order($order_id, $order_data) {
        $result = $this->woocommerce->put('orders/' . $order_id, $order_data);
        
        if (isset($result->message)) {
            http_response_code(400);
            echo json_encode(array("message" => $result->message));
        } else {
            http_response_code(200);
            echo json_encode($result);
        }
    }
    
    public function delete_order($order_id) {
        $result = $this->woocommerce->delete('orders/' . $order_id, ['force' => true]);
        
        if ($result) {
            http_response_code(204);
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "Order not found."));
        }
    }
}


//This code sets up an OrdersController class that communicates with the WooCommerce REST API to perform CRUD operations on orders. 
// The __construct() method sets up an instance of the WooCommerce API client using the configuration values from config.php.