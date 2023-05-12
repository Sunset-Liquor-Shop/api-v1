// jwtHelper.js

const jwt = require('jsonwebtoken');
const { jwtSecret } = require('../config/config');

// Generate JWT
const generateToken = (payload) => {
    // Implementation here
};

// Verify JWT
const verifyToken = (token) => {
    // Implementation here
};

module.exports = {
    generateToken,
    verifyToken,
};