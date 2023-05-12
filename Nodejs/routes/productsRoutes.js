// productsRoutes.js

const express = require('express');
const router = express.Router();
const productsController = require('../controllers/productsController');

// Define product routes
router.get('/products', productsController.getAllProducts);
router.get('/products/:id', productsController.getProductById);
router.post('/products', productsController.createProduct);
router.put('/products/:id', productsController.updateProduct);
router.delete('/products/:id', productsController.deleteProduct);

module.exports = router;