# REST API for Sunset

This is a REST API built using PHP and the WooCommerce API library. It provides endpoints to retrieve products and orders from a WooCommerce store and to create new orders.

# Installation

To install the API, follow these steps:

Clone the repository to your local machine.
Install Composer, if you haven't already.
Run composer install to install dependencies.
Rename the .env.example file to .env and set the environment variables to match your environment.
Create a new MySQL database and import the database.sql file to create the required tables.
Configure your web server to serve the API files.
Usage

To use the API, make requests to the available endpoints using HTTP methods such as GET, POST, PUT, and DELETE. The following endpoints are available:

# Authentication
POST /auth/login - Authenticate a user and generate a JWT token.
Products
GET /products - Retrieve a list of products.
GET /products/{id} - Retrieve a single product by ID.
POST /products - Create a new product.
PUT /products/{id} - Update an existing product by ID.
DELETE /products/{id} - Delete a product by ID.
Orders
GET /orders - Retrieve a list of orders.
GET /orders/{id} - Retrieve a single order by ID.
POST /orders - Create a new order.
PUT /orders/{id} - Update an existing order by ID.
DELETE /orders/{id} - Delete an order by ID.