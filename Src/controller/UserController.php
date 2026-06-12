<?php
 
// ============================================================
// src/Controller/UserController.php
// ============================================================

namespace App\Controller;

use App\Repository\StockBatchRepository;
use App\Repository\UserRepository;

class UserController
{
 
    private UserRepository $userRepo;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

   public function loginAction()
    {
       require __DIR__ . '/../../templates/auth/login.php';
    }

         public function loginSubmitAction()
     {
         session_start();

         $email = $_POST['email'];
         $password = $_POST['password'];

         $user = $this->userRepo->login($email, $password);

 

         if (!$user) {
            $_SESSION['error'] = "Invalid email or password.";
            header('Location: index.php?action=login');
            exit;
         }

         $_SESSION['user'] = [
             'id'      => $user->id,
             'name'    => $user->name,
             'email'   => $user->email,
             'role'    => $user->role
         ];

         if ($user->role === 'ADMIN' || $user->role === 'PHARMACIEN' || $user->role === 'PREPARATEUR') {
             header('Location: index.php?action=dashboard');
             exit;
         }

 
     }


     public function logoutAction()
     {
         session_start();
         session_destroy();
         header('Location: index.php?action=login');
         exit;
     }

}