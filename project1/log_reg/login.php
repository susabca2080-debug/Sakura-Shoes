<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
   
    <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md">
        
          <!-- <--cross icon  -->
     <a href="../index.php" class=" top-4 right-4 text-red-500 hover:text-gray-700">
        <img src="../crudop/images/arrow.png" alt="cross icon" class="h-6 w-6">
    </a>

        <!-- Error Message -->
        <?php
        if (isset($_SESSION['error'])) {
            echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 ">';
            echo $_SESSION['error'];
            echo '</div>';
            unset($_SESSION['error']);
        }
        ?>

        <!-- Logo / Title -->
        <h2 class="text-2xl font-bold text-center mb-6">Login</h2>

        <!-- Login Form -->
        <form method="POST" action="login_process.php">

            <div class="mb-4">
                <label class="block text-sm mb-1">Email</label>
                <input type="email" name="email" required
                    class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300">
            </div>

            <div class="mb-2">
                <label class="block text-sm mb-1">Password</label>
                <input type="password" name="password" required
                    class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300">
            </div>

            <!-- Forgot password -->
            <div class="text-right mb-4">
                <a href="forgot_password.php"
                   class="text-sm text-blue-500 hover:underline">
                   Forgot password?
                </a>
            </div>

            <button name="login"
                class="w-full bg-green-500 hover:bg-green-600 text-white py-2 rounded-lg font-semibold">
                Login
            </button>

        </form>
        <!-- logine with google gmail -->
        <!-- <div class="flex items-center my-4">
            <hr class="flex-grow border-t border-gray-300">
            <span class="mx-2 text-gray-500">or</span>
            <hr class="flex-grow border-t border-gray-300">
        </div>
        <a href="google_login.php"
           class="w-full flex items-center justify-center border border-gray-300 rounded-lg py-2 hover:bg-gray-100">
            <img src="../google-logo.png" alt="Google Logo" class="h-5 w-5 mr-2">
            <span class="text-gray-700 font-semibold">Login with Google</span>
        </a>
         -->
        <!-- Register -->
        <p class="text-center text-sm mt-6">
            Don't have an account?
            <a href="register.php" class="text-blue-500 font-semibold hover:underline">
                Register
            </a>
        </p>

    </div>

</body>
</html>