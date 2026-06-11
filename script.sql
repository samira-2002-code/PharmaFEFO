CREATE DATABASE pharmafefo;

USE pharmafefo;

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

CREATE TABLE stock_batches (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    lot_number VARCHAR(50) NOT NULL,
    quantity INT NOT NULL,
    expiration_date DATE NOT NULL,
    status VARCHAR(20) DEFAULT 'OK'
);