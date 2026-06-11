<?php

require_once __DIR__ . '/../repository/StockBatchRepository.php';

class DashboardController
{
    private StockBatchRepository $repo;

    public function __construct()
    {
        $this->repo = new StockBatchRepository();
    }

    public function index()
    {
        $batches = $this->repo->getAll();

        include __DIR__ . '/../views/dashboard.php';
    }
}