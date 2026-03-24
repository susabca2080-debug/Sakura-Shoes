<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md">

        <h2 class="text-2xl font-bold text-center mb-6">Forgot Password</h2>

        <form method="POST" action="forgot_password_process.php">

            <input type="email" name="email" placeholder="Enter your email" required
                class="w-full border rounded-lg px-3 py-2 mb-4">

            <button
                class="w-full bg-blue-500 hover:bg-blue-600 text-white py-2 rounded-lg font-semibold">
                Send Reset Link
            </button>
        </form>

        <p class="text-center text-sm mt-6">
            Remember password?
            <a href="login.php" class="text-blue-500 hover:underline">
                Login
            </a>
        </p>

    </div>

</body>
</html>