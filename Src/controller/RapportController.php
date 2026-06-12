<?php

// ============================================================
// src/Controller/RapportController.php
// ============================================================

namespace App\Controller;

use App\Repository\StockBatchRepository;

class RapportController
{
    private StockBatchRepository $repo;

    public function __construct(StockBatchRepository $repo)
    {
        $this->repo = $repo;
    }

    public function index(): void
    {
        $expiredLots = $this->repo->findExpired();

        // Calcul de la valeur totale perdue
        $totalLost = 0.0;
        foreach ($expiredLots as $lot) {
            // On garde la valeur originale avant mise à 0
            // On utilise la quantité stockée avant déclaration (peut être 0 si déclarée)
            $totalLost += $lot->getTotalValue();
        }

        $countExpired = count($expiredLots);

        require __DIR__ . '/../../templates/rapport/index.php';
    }
}
