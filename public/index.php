<?php

require_once __DIR__ . '/../app/controllers/DashboardController.php';
require_once __DIR__ . '/../app/controllers/StockController.php';

$page = $_GET['page'] ?? 'dashboard';

switch ($page) {

    case 'stocks':
        (new StockController())->index();
        break;

    case 'create-stock':
        (new StockController())->create();
        break;

    case 'store-stock':
        (new StockController())->store();
        break;

    default:
        (new DashboardController())->index();
        break;
}