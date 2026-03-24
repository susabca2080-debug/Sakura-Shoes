<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md">
        <a href="login.php" class=" top-4 right-4 text-red-500 hover:text-gray-700">
        <img src="../crudop/images/arrow.png" alt="cross icon" class="h-6 w-6">
    </a>

        <h2 class="text-2xl font-bold text-center mb-6">Create Account</h2>

        <form method="POST" action="register_process.php" enctype="multipart/form-data">

            <input type="text" id="name" name="full_name" placeholder="Full Name" required
                class="w-full border rounded-lg px-3 py-2 mb-3">
                <span class="name_error hidden"></span>

            <input type="email"  id="email" name="email" placeholder="Email" required
                class="w-full border rounded-lg px-3 py-2 mb-3">
                <span class="email_error hidden"></span>

            <input type="password" id="password" name="password" placeholder="Password" required
                class="w-full border rounded-lg px-3 py-2 mb-3">
                <span class="password_error hidden"></span>
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required
                class="w-full border rounded-lg px-3 py-2 mb-3">    
                <span class="confirm_password_error hidden"></span>

            <input type="file" name="profile_picture"
                class="w-full border rounded-lg px-3 py-2 mb-4">

            <button name="register"
                class="w-full bg-green-500 hover:bg-green-600 text-white py-2 rounded-lg font-semibold">
                Register
            </button>
        </form>

        <p class="text-center text-sm mt-6">
            Already have an account?
            <a href="login.php" class="text-blue-500 font-semibold hover:underline">
                Login
            </a>
        </p>

    </div>
<script>
// error 
let nameerror = document.querySelector(".name_error");
let emailerror = document.querySelector(".email_error");
let passworderror = document.querySelector(".password_error");
let confirm_passworderror = document.querySelector(".confirm_password_error");
// input fields

    let emailinput = document.querySelector("#email");
    emailpattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;
    let name = document.querySelector("#name");
    namepattern = /^[a-zA-Z\s]{3,}$/;
    let passwordinput = document.querySelector("#password");
    passwordpattern =/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
    let confirm_passwordinput = document.querySelector("#confirm_password");
    name_length = name.value.length;
// form submit event
    const form = document.querySelector("form");
    form.addEventListener("submit", (e) => {
        let isValid = true;
        // Name validation
        if (!name.value.match(namepattern)) {
            nameerror.classList.remove("hidden");
            nameerror.classList.add("text-red-500");
            nameerror.innerText = "Please enter a valid name (at least 3 letters).";
            isValid = false;
        } else {
            nameerror.classList.add("hidden");
        }
        // Email validation
        if (!emailinput.value.match(emailpattern)) {
            emailerror.classList.remove("hidden");
            emailerror.classList.add("text-red-500");
            emailerror.innerText = "Please enter a valid email address.";
            isValid = false;
        } else {
            emailerror.classList.add("hidden");
        }
        // Password validation
        if (!passwordinput.value.match(passwordpattern)) {
            passworderror.classList.remove("hidden");
            passworderror.classList.add("text-red-500");
            passworderror.innerText = "Password must be at least 8 characters long and include uppercase, lowercase, number, and special character.";
            isValid = false;
        } else {
            passworderror.classList.add("hidden");
        }
        // Confirm Password validation
        if (confirm_passwordinput.value !== passwordinput.value || confirm_passwordinput.value === "") {
            confirm_passworderror.classList.remove("hidden");
            confirm_passworderror.classList.add("text-red-500");
            confirm_passworderror.innerText = "Passwords do not match.";
            isValid = false;
        } else {
            confirm_passworderror.classList.add("hidden");
        }

        if (!isValid) {
            e.preventDefault();
        }
    });
</script>
</body>
</html>