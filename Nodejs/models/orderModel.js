// orderModel.js

const mongoose = require('mongoose');

const orderSchema = new mongoose.Schema({
    user: {
        type: mongoose.Schema.Types.ObjectId,
        ref: 'User',
        required: true,
    },
    products: [{
        type: mongoose.Schema.Types.ObjectId,
        ref: 'Product',
    }, ],
    totalPrice: {
        type: Number,
        required: true,
    },
    // Other order properties...
});

const Order = mongoose.model('Order', orderSchema);

module.exports = Order;