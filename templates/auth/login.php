<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
    
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-600 via-cyan-500 to-emerald-500">

  <div class="w-full max-w-md">

    <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">

      <!-- HEADER -->
      <div class="text-center p-8 bg-gray-50">
        <div class="w-20 h-20 mx-auto rounded-full bg-gradient-to-r from-blue-600 to-emerald-500 flex items-center justify-center">
          <i class="fa-solid fa-capsules text-white text-3xl"></i>
        </div>

        <h1 class="text-2xl font-bold mt-4">Login</h1>
        <p class="text-gray-500 text-sm">Welcome back</p>

        <?php 
          if (isset($_SESSION['error'])) {
              echo '<p class="text-red-500 text-sm mt-2">' . $_SESSION['error'] . '</p>';
              unset($_SESSION['error']);
          }
        ?>
      </div>

      <!-- BODY -->
      <div class="p-8 space-y-5">

        <form action="index.php?action=login_submit" method="POST" class="space-y-5">

          <!-- EMAIL -->
          <div>
            <label class="text-sm font-semibold">Email</label>
            <div class="relative mt-1">
              <i class="fa-solid fa-envelope absolute left-3 top-3 text-gray-400"></i>
              <input type="email" name="email"
                class="w-full pl-10 pr-3 py-3 border rounded-xl focus:ring-2 focus:ring-blue-500 outline-none"
                placeholder="Email"  >
            </div>
          </div>

          <!-- PASSWORD -->
          <div>
            <label class="text-sm font-semibold">Password</label>
            <div class="relative mt-1">
              <i class="fa-solid fa-lock absolute left-3 top-3 text-gray-400"></i>

              <input type="password" name="password"
                class="w-full pl-10 pr-10 py-3 border rounded-xl focus:ring-2 focus:ring-blue-500 outline-none"
                placeholder="Password"  >

              <button type="button"
                class="absolute right-3 top-3 text-gray-400">
                <i class="fa-solid fa-eye"></i>
              </button>
            </div>
          </div>

          <!-- BUTTON -->
          <button type="submit"
            class="w-full py-3 rounded-xl text-white font-bold bg-gradient-to-r from-blue-600 to-emerald-500 hover:opacity-90">
            Login
          </button>

        </form>

      </div>

    </div>

  </div>

</body>

</html>