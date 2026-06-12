<?php

// ============================================================
// src/Controller/DashboardController.php
// ============================================================

namespace App\Controller;

use App\Repository\StockBatchRepository;

class DashboardController
{
    private StockBatchRepository $repo;

    public function __construct(StockBatchRepository $repo)
    {
        $this->repo = $repo;
    }

    public function index(): void
    {
        $lots         = $this->repo->findAll();
        $totalLots    = count($lots);
        $orangeAlerts = $this->repo->countOrangeAlerts();
        $redAlerts    = $this->repo->countRedAlerts();

        require __DIR__ . '/../../templates/dashboard/index.php';
    }
}
