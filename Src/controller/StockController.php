<?php

require_once __DIR__ . '/../Repository/StockBatchRepository.php';

class StockController
{
    private StockBatchRepository $repo;

    public function __construct()
    {
        $this->repo = new StockBatchRepository();
    }

    // afficher formulaire
    public function create()
    {
        require __DIR__ . '/../../templates/stock/create.php';
    }

    // traiter ajout lot
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $medicament = $_POST['medicament'];
            $lot = $_POST['lot_number'];
            $expiration = $_POST['expiration_date'];
            $quantity = $_POST['quantity'];

            // validation simple
            if (empty($expiration) || strtotime($expiration) < strtotime(date('Y-m-d'))) {
                die("Date invalide");
            }

            $this->repo->create($medicament, $lot, $expiration, $quantity);

            header("Location: index.php?page=stocks");
            exit;
        }
    }

    public function index()
    {
        $batches = $this->repo->getAll();
        require __DIR__ . '/../../templates/stock/index.php';
    }
}