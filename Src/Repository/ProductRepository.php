<?php

// ============================================================
// src/Repository/ProductRepository.php
// ============================================================

namespace App\Repository;

use App\Entity\Product;
use PDO;

class ProductRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Retourne tous les produits
     * @return Product[]
     */
    public function findAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM products ORDER BY name ASC");
        return array_map(fn($row) => new Product($row), $stmt->fetchAll());
    }

    /**
     * Retourne un produit par son ID
     */
    public function findById(int $id): ?Product
    {
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ? new Product($row) : null;
    }
}
