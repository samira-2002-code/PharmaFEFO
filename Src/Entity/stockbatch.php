<?php

class StockBatch
{
    private int $id;
    private string $lotNumber;
    private string $expirationDate;
    private int $quantity;

    public function __construct(
        int $id,
        string $lotNumber,
        string $expirationDate,
        int $quantity
    ) {
        $this->id = $id;
        $this->lotNumber = $lotNumber;
        $this->expirationDate = $expirationDate;
        $this->quantity = $quantity;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getLotNumber()
    {
        return $this->lotNumber;
    }

    public function getExpirationDate()
    {
        return $this->expirationDate;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function getStatus(): string
    {
        $today = new DateTime();
        $expiration = new DateTime($this->expirationDate);

        if ($expiration < $today) {
            return 'EXPIRED';
        }

        $days = (int)$today->diff($expiration)->format('%a');

        if ($days <= 30) {
            return 'CRITICAL';
        }

        if ($days <= 90) {
            return 'WARNING';
        }

        return 'OK';
    }
}