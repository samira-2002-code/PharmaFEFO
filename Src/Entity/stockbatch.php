<?php

// ============================================================
// src/Entity/StockBatch.php
// ============================================================

namespace App\Entity;

class StockBatch
{
    public int $id;
    public int $productId;
    public string $batchNumber;
    public int $quantity;
    public string $expiryDate;
    public string $status;
    public string $createdAt;

    // Données jointes depuis la table products
    public string $productName = '';
    public string $productReference = '';
    public float $unitPrice = 0.0;
    public string $unit = '';

    // Constantes de statut
    const STATUS_ACTIVE  = 'ACTIVE';
    const STATUS_EXPIRED = 'EXPIRED';

    // Seuils d'alerte (en jours)
    const ALERT_ORANGE = 90;
    const ALERT_RED    = 30;

    public function __construct(array $data = [])
    {
        $this->id               = $data['id'] ?? 0;
        $this->productId        = $data['product_id'] ?? 0;
        $this->batchNumber      = $data['batch_number'] ?? '';
        $this->quantity         = (int) ($data['quantity'] ?? 0);
        $this->expiryDate       = $data['expiry_date'] ?? '';
        $this->status           = $data['status'] ?? self::STATUS_ACTIVE;
        $this->createdAt        = $data['created_at'] ?? '';
        $this->productName      = $data['product_name'] ?? '';
        $this->productReference = $data['product_reference'] ?? '';
        $this->unitPrice        = (float) ($data['unit_price'] ?? 0.0);
        $this->unit             = $data['unit'] ?? '';
    }

    /**
     * Calcule le nombre de jours restants avant péremption
     */
    public function getDaysRemaining(): int
    {
        $today  = new \DateTime('today');
        $expiry = new \DateTime($this->expiryDate);
        $diff   = $today->diff($expiry);

        // Si la date est dépassée, retourne un nombre négatif
        return $expiry >= $today ? (int) $diff->days : -(int) $diff->days;
    }

    /**
     * Retourne le niveau d'alerte : 'expired', 'red', 'orange', 'green'
     */
    public function getAlertLevel(): string
    {
        if ($this->status === self::STATUS_EXPIRED) {
            return 'expired';
        }

        $days = $this->getDaysRemaining();

        if ($days < 0) return 'expired';
        if ($days <= self::ALERT_RED) return 'red';
        if ($days <= self::ALERT_ORANGE) return 'orange';
        return 'green';
    }

    /**
     * Retourne la valeur totale du lot (quantité × prix unitaire)
     */
    public function getTotalValue(): float
    {
        return $this->quantity * $this->unitPrice;
    }

    /**
     * Vérifie si le lot est expiré
     */
    public function isExpired(): bool
    {
        return $this->status === self::STATUS_EXPIRED || $this->getDaysRemaining() < 0;
    }
}
