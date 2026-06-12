<?php

if (isset($_GET['page'])) {
    require __DIR__ . '/public/index.php';
    exit;
}

require_once __DIR__ . "/config/database.php";

spl_autoload_register(function (string $class) {
    $prefix = 'App\\';
    $base   = __DIR__ . '/src/';

    if (str_starts_with($class, $prefix)) {
        $relative = substr($class, strlen($prefix));
        $file = $base . str_replace('\\', '/', $relative) . '.php';
        if (file_exists($file)) {
            require_once $file;
        }
    }
});

require_once __DIR__ . "/src/Repository/UserRepository.php";
require_once __DIR__ . "/src/Controller/UserController.php";
require_once __DIR__ . "/src/Controller/DashboardController.php";
require_once __DIR__ . "/src/Repository/StockBatchRepository.php";

$pdo = getConnection();
$userRepo = new \App\Repository\UserRepository($pdo);
$stockBatchRepo = new \App\Repository\StockBatchRepository($pdo);

if (isset($_GET['action'])) {
    $action = $_GET['action'];

    switch ($action) {
        case 'login':
          $user = new \App\Controller\UserController($userRepo);
          $user->loginAction();
            break;

        case 'login_submit':
            $user = new \App\Controller\UserController($userRepo);
            $user->loginSubmitAction();
            break;

        case 'dashboard':
              $user = new \App\Controller\DashboardController($stockBatchRepo);
              $user->index();
            exit;    
       
        case 'logout':
            $user = new \App\Controller\UserController($userRepo);
            $user->logoutAction();
            break;
      
           

  }
  
  
 }else {
        $user = new \App\Controller\UserController($userRepo);
          $user->loginAction();
 }