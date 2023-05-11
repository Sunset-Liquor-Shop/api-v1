<?php

class Order
{
    private $conn;
    private $table_name = "orders";

    public $id;
    public $customer_id;
    public $status;
    public $total;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create()
    {
        $query = "INSERT INTO " . $this->table_name . " (customer_id, status, total) VALUES (:customer_id, :status, :total)";
        $stmt = $this->conn->prepare($query);

        $this->customer_id = htmlspecialchars(strip_tags($this->customer_id));
        $this->status = htmlspecialchars(strip_tags($this->status));
        $this->total = htmlspecialchars(strip_tags($this->total));

        $stmt->bindParam(':customer_id', $this->customer_id);
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':total', $this->total);

        if ($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }

        return false;
    }

    public function read()
    {
        $query = "SELECT id, customer_id, status, total FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    public function read_one()
    {
        $query = "SELECT customer_id, status, total FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->customer_id = $row['customer_id'];
        $this->status = $row['status'];
        $this->total = $row['total'];
    }
}
