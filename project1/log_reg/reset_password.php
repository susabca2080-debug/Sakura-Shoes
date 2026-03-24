<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

<div class="bg-white p-8 rounded-lg shadow w-full max-w-md">
    <h2 class="text-2xl font-bold mb-4 text-center">Reset Password</h2>

    <form method="POST" action="reset_password_process.php">
        <input type="hidden" name="email" value="<?php echo $_GET['email']; ?>">

        <input type="password" name="password" placeholder="New Password" required
            class="w-full border p-2 mb-3 rounded">

        <input type="password" name="confirm_password" placeholder="Confirm Password" required
            class="w-full border p-2 mb-4 rounded">

        <button class="w-full bg-green-500 text-white p-2 rounded">
            Update Password
        </button>
    </form>
</div>

</body>
</html>