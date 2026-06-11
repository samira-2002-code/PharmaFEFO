<?php

require_once __DIR__ . '/../../config/database.php';

class StockBatchRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::connect();
    }

    public function getAll()
    {
        $sql = "SELECT * FROM stock_batches ORDER BY expiration_date ASC";
        $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}