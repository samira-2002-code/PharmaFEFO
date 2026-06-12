<?php


namespace App\Entity;

class Product
{
    public int $id;
    public string $name;
    public string $reference;
    public string $unit;
    public float $unitPrice;
    public string $createdAt;

    public function __construct(array $data = [])
    {
        $this->id        = $data['id'] ?? 0;
        $this->name      = $data['name'] ?? '';
        $this->reference = $data['reference'] ?? '';
        $this->unit      = $data['unit'] ?? 'comprimé';
        $this->unitPrice = (float) ($data['unit_price'] ?? 0.0);
        $this->createdAt = $data['created_at'] ?? '';
    }
}

