<?php
require_once __DIR__.'/../../config/database.php';
 class StockBatchRepository{
    private $pdo;
    public function __construct()
    {
        $this->pdo= Database::connect();
    }
    public function getAll(){
        $sql="SELECT *FROM stock_batches ORDER BY expiration_date ASC ";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
 }

