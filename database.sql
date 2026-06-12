-- ============================================================
-- PharmaFEFO - Script SQL complet
-- ============================================================

CREATE DATABASE IF NOT EXISTS pharmafefo1 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE pharmafefo1;

-- Table des produits (médicaments)
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    reference VARCHAR(50) NOT NULL UNIQUE,
    unit VARCHAR(20) NOT NULL DEFAULT 'comprimé',
    unit_price DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Table des lots de stock
CREATE TABLE stock_batches (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    batch_number VARCHAR(50) NOT NULL,
    quantity INT NOT NULL DEFAULT 0,
    expiry_date DATE NOT NULL,
    status ENUM('ACTIVE', 'EXPIRED') NOT NULL DEFAULT 'ACTIVE',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Données de démonstration
INSERT INTO products (name, reference, unit, unit_price) VALUES
('Paracétamol 500mg', 'PARA-500', 'comprimé', 0.50),
('Amoxicilline 250mg', 'AMOX-250', 'gélule', 1.20),
('Ibuprofène 400mg', 'IBUP-400', 'comprimé', 0.80),
('Oméprazole 20mg', 'OMEP-020', 'gélule', 1.50),
('Métronidazole 250mg', 'METR-250', 'comprimé', 0.70);

INSERT INTO stock_batches (product_id, batch_number, quantity, expiry_date, status) VALUES
(1, 'LOT-2024-001', 500, DATE_ADD(CURDATE(), INTERVAL 120 DAY), 'ACTIVE'),
(1, 'LOT-2024-002', 200, DATE_ADD(CURDATE(), INTERVAL 25 DAY), 'ACTIVE'),
(2, 'LOT-2024-003', 300, DATE_ADD(CURDATE(), INTERVAL 60 DAY), 'ACTIVE'),
(2, 'LOT-2024-004', 150, DATE_SUB(CURDATE(), INTERVAL 5 DAY), 'EXPIRED'),
(3, 'LOT-2024-005', 400, DATE_ADD(CURDATE(), INTERVAL 15 DAY), 'ACTIVE'),
(4, 'LOT-2024-006', 250, DATE_ADD(CURDATE(), INTERVAL 200 DAY), 'ACTIVE'),
(5, 'LOT-2024-007', 100, DATE_ADD(CURDATE(), INTERVAL 45 DAY), 'ACTIVE');
