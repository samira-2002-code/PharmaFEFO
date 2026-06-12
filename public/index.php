<?php

// ============================================================
// public/index.php - Routeur principal
// ============================================================

declare(strict_types=1);

// Autoloader PSR-4 simple (sans Composer)
spl_autoload_register(function (string $class) {
    $base = __DIR__ . '/../src/';
    $file = $base . str_replace(['App\\', '\\'], ['', '/'], $class) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

require_once __DIR__ . '/../config/database.php';

use App\Repository\StockBatchRepository;
use App\Repository\ProductRepository;
use App\Controller\DashboardController;
use App\Controller\LotController;
use App\Controller\RapportController;

// Instanciation des dépendances
$pdo         = getConnection();
$batchRepo   = new StockBatchRepository($pdo);
$productRepo = new ProductRepository($pdo);

// Lecture des paramètres de route
$page   = $_GET['page']   ?? 'dashboard';
$action = $_GET['action'] ?? 'index';
$id     = isset($_GET['id']) ? (int) $_GET['id'] : null;

// Routage
switch ($page) {

    case 'dashboard':
        $ctrl = new DashboardController($batchRepo);
        $ctrl->index();
        break;

    case 'lots':
        $ctrl = new LotController($batchRepo, $productRepo);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($action === 'store')       $ctrl->store();
            elseif ($action === 'update')  $ctrl->update($id);
            elseif ($action === 'fefo')    $ctrl->fefoOut();
        } else {
            if ($action === 'create')         $ctrl->create();
            elseif ($action === 'edit')       $ctrl->edit($id);
            elseif ($action === 'delete')     $ctrl->delete($id);
            elseif ($action === 'expire')     $ctrl->declareExpired($id);
            else                              $ctrl->index();
        }
        break;

    case 'rapport':
        $ctrl = new RapportController($batchRepo);
        $ctrl->index();
        break;

    default:
        $ctrl = new DashboardController($batchRepo);
        $ctrl->index();
        break;
}
