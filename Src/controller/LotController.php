<?php

// ============================================================
// src/Controller/LotController.php
// ============================================================

namespace App\Controller;

use App\Repository\StockBatchRepository;
use App\Repository\ProductRepository;

class LotController
{
    private StockBatchRepository $batchRepo;
    private ProductRepository    $productRepo;

    public function __construct(StockBatchRepository $batchRepo, ProductRepository $productRepo)
    {
        $this->batchRepo   = $batchRepo;
        $this->productRepo = $productRepo;
    }

    /**
     * Liste tous les lots
     */
    public function index(): void
    {
        $lots     = $this->batchRepo->findAll();
        $products = $this->productRepo->findAll();
        require __DIR__ . '/../../templates/lots/index.php';
    }

    /**
     * Affiche le formulaire d'ajout
     */
    public function create(): void
    {
        $products = $this->productRepo->findAll();
        $errors   = [];
        require __DIR__ . '/../../templates/lots/form.php';
    }

    /**
     * Enregistre un nouveau lot
     */
    public function store(): void
    {
        $errors = $this->validate($_POST);

        if (!empty($errors)) {
            $products = $this->productRepo->findAll();
            require __DIR__ . '/../../templates/lots/form.php';
            return;
        }

        $this->batchRepo->create([
            'product_id'   => (int) $_POST['product_id'],
            'batch_number' => trim($_POST['batch_number']),
            'quantity'     => (int) $_POST['quantity'],
            'expiry_date'  => $_POST['expiry_date'],
        ]);

        header('Location: index.php?page=lots&success=created');
        exit;
    }

    /**
     * Affiche le formulaire de modification
     */
    public function edit(int $id): void
    {
        $lot      = $this->batchRepo->findById($id);
        $products = $this->productRepo->findAll();
        $errors   = [];

        if (!$lot) {
            header('Location: index.php?page=lots');
            exit;
        }

        require __DIR__ . '/../../templates/lots/form.php';
    }

    /**
     * Met à jour un lot
     */
    public function update(int $id): void
    {
        $lot    = $this->batchRepo->findById($id);
        $errors = $this->validate($_POST);

        if (!empty($errors)) {
            $products = $this->productRepo->findAll();
            require __DIR__ . '/../../templates/lots/form.php';
            return;
        }

        $this->batchRepo->update($id, [
            'product_id'   => (int) $_POST['product_id'],
            'batch_number' => trim($_POST['batch_number']),
            'quantity'     => (int) $_POST['quantity'],
            'expiry_date'  => $_POST['expiry_date'],
        ]);

        header('Location: index.php?page=lots&success=updated');
        exit;
    }

    /**
     * Supprime un lot
     */
    public function delete(int $id): void
    {
        $this->batchRepo->delete($id);
        header('Location: index.php?page=lots&success=deleted');
        exit;
    }

    /**
     * Déclare un lot comme périmé
     */
    public function declareExpired(int $id): void
    {
        $this->batchRepo->declareExpired($id);
        header('Location: index.php?page=lots&success=expired');
        exit;
    }

    /**
     * Sortie FEFO : décrémente le lot dont la date est la plus proche
     */
    public function fefoOut(): void
    {
        $productId = (int) ($_POST['product_id'] ?? 0);
        $qty       = (int) ($_POST['quantity'] ?? 0);

        $lot = $this->batchRepo->findFefoLot($productId);

        if (!$lot) {
            header('Location: index.php?page=lots&error=no_fefo_lot');
            exit;
        }

        if ($qty > $lot->quantity) {
            header('Location: index.php?page=lots&error=insufficient_stock');
            exit;
        }

        $this->batchRepo->decrementQuantity($lot->id, $qty);
        header('Location: index.php?page=lots&success=fefo_out&lot=' . $lot->batchNumber);
        exit;
    }

    /**
     * Validation simple des données du formulaire
     */
    private function validate(array $data): array
    {
        $errors = [];

        if (empty($data['product_id'])) {
            $errors[] = "Veuillez sélectionner un produit.";
        }
        if (empty($data['batch_number'])) {
            $errors[] = "Le numéro de lot est obligatoire.";
        }
        if (!isset($data['quantity']) || (int)$data['quantity'] < 0) {
            $errors[] = "La quantité doit être un nombre positif.";
        }
        if (empty($data['expiry_date'])) {
            $errors[] = "La date de péremption est obligatoire.";
        }

        return $errors;
    }
}
