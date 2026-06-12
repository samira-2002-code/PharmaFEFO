<?php

// ============================================================
// src/Repository/StockBatchRepository.php
// ============================================================

namespace App\Repository;

use App\Entity\StockBatch;
use PDO;

class StockBatchRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Requête SQL de base avec jointure produit
     */
    private function baseQuery(): string
    {
        return "
            SELECT
                sb.*,
                p.name        AS product_name,
                p.reference   AS product_reference,
                p.unit_price  AS unit_price,
                p.unit        AS unit
            FROM stock_batches sb
            JOIN products p ON sb.product_id = p.id
        ";
    }

    /**
     * Retourne tous les lots, triés par date de péremption (FEFO)
     * @return StockBatch[]
     */
    public function findAll(): array
    {
        $stmt = $this->pdo->query($this->baseQuery() . " ORDER BY sb.expiry_date ASC");
        return array_map(fn($row) => new StockBatch($row), $stmt->fetchAll());
    }

    /**
     * Retourne un lot par son ID
     */
    public function findById(int $id): ?StockBatch
    {
        $stmt = $this->pdo->prepare($this->baseQuery() . " WHERE sb.id = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ? new StockBatch($row) : null;
    }

    /**
     * FEFO : retourne le lot ACTIF avec la date de péremption la plus proche
     * pour un produit donné (avec stock > 0)
     */
    public function findFefoLot(int $productId): ?StockBatch
    {
        $sql = $this->baseQuery() . "
            WHERE sb.product_id = :product_id
              AND sb.status     = 'ACTIVE'
              AND sb.quantity   > 0
              AND sb.expiry_date >= CURDATE()
            ORDER BY sb.expiry_date ASC
            LIMIT 1
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':product_id' => $productId]);
        $row = $stmt->fetch();
        return $row ? new StockBatch($row) : null;
    }

    /**
     * Compte les alertes orange (entre 30 et 90 jours)
     */
    public function countOrangeAlerts(): int
    {
        $stmt = $this->pdo->query("
            SELECT COUNT(*) FROM stock_batches
            WHERE status = 'ACTIVE'
              AND expiry_date BETWEEN DATE_ADD(CURDATE(), INTERVAL 30 DAY)
                                 AND DATE_ADD(CURDATE(), INTERVAL 90 DAY)
        ");
        return (int) $stmt->fetchColumn();
    }

    /**
     * Compte les alertes rouge (moins de 30 jours, non expirés)
     */
    public function countRedAlerts(): int
    {
        $stmt = $this->pdo->query("
            SELECT COUNT(*) FROM stock_batches
            WHERE status = 'ACTIVE'
              AND expiry_date BETWEEN CURDATE()
                                 AND DATE_ADD(CURDATE(), INTERVAL 30 DAY)
        ");
        return (int) $stmt->fetchColumn();
    }

    /**
     * Retourne les lots expirés (statut EXPIRED)
     * @return StockBatch[]
     */
    public function findExpired(): array
    {
        $stmt = $this->pdo->query($this->baseQuery() . " WHERE sb.status = 'EXPIRED' ORDER BY sb.expiry_date ASC");
        return array_map(fn($row) => new StockBatch($row), $stmt->fetchAll());
    }

    /**
     * Crée un nouveau lot
     */
    public function create(array $data): bool
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO stock_batches (product_id, batch_number, quantity, expiry_date)
            VALUES (:product_id, :batch_number, :quantity, :expiry_date)
        ");
        return $stmt->execute([
            ':product_id'   => $data['product_id'],
            ':batch_number' => $data['batch_number'],
            ':quantity'     => $data['quantity'],
            ':expiry_date'  => $data['expiry_date'],
        ]);
    }

    /**
     * Met à jour un lot existant
     */
    public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE stock_batches
            SET product_id   = :product_id,
                batch_number = :batch_number,
                quantity     = :quantity,
                expiry_date  = :expiry_date
            WHERE id = :id
        ");
        return $stmt->execute([
            ':id'           => $id,
            ':product_id'   => $data['product_id'],
            ':batch_number' => $data['batch_number'],
            ':quantity'     => $data['quantity'],
            ':expiry_date'  => $data['expiry_date'],
        ]);
    }

    /**
     * Supprime un lot
     */
    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM stock_batches WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Déclare un lot comme périmé (statut EXPIRED, quantité = 0)
     */
    public function declareExpired(int $id): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE stock_batches SET status = 'EXPIRED', quantity = 0 WHERE id = :id
        ");
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Décrémente la quantité d'un lot (sortie FEFO)
     */
    public function decrementQuantity(int $id, int $qty): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE stock_batches SET quantity = quantity - :qty WHERE id = :id AND quantity >= :qty
        ");
        return $stmt->execute([':id' => $id, ':qty' => $qty]);
    }
}
