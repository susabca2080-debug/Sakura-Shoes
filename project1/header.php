<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/sakurashoes/project1/output.css"> 
    <script src="https://unpkg.com/lucide@0.254.0/dist/lucide.min.js"></script>  
    
</head>
<body>
    <?php
     if (session_status() === PHP_SESSION_NONE) {
         session_start();
         session_regenerate_id(true);
     }
     ?>
  <!-- Header -->
    <header class="bg-white shadow-lg sticky top-0 z-50">
        <div class="container mx-auto  px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <!-- Logo -->
                <div class="flex items-center space-x-2">
                 <div class="flex items-center space-x-4">
            <img src="Untitleddesign.png" alt="Logo" class="h-20 w-20 rounded-full border-1  border-white shadow-md ">
            <span class="text-red-400 text-2xl font-bold tracking-wide">Sakura Shoes</span>
                </div>
                </div>

                <!-- Desktop Navigation -->
                <nav class="hidden md:flex items-center justify-between space-x-5">
                    <a href="index.php" class="text-slate-700 hover:text-amber-500 transition-colors duration-200 font-medium">Home</a>
                    <a href="products.php" class="text-slate-700 hover:text-amber-500 transition-colors duration-200 font-medium">Featured</a>
                
                    <div id="cat" class="relative group">
                        <span class="text-slate-700  transition-colors cursor-pointer duration-200 font-medium">Categories</span>
                        <div class="dropdownbox absolute hidden left-0 mt-0 w-30 bg-white rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-10">
                            <a href="mensproduct.php" class="block px-4 py-2 text-slate-700 hover:text-amber-500 hover:bg-gray-100 rounded-t-lg">Mens</a>
                            <a href="womensproduct.php" class="block px-4 py-2 text-slate-700 hover:text-amber-500 hover:bg-gray-100">Womens</a>
                            <a href="kidsproduct.php" class="block px-4 py-2 text-slate-700 hover:text-amber-500 hover:bg-gray-100">Kids</a>
                            <a href="unisexproducts.php" class="block px-4 py-2 text-slate-700 hover:text-amber-500 hover:bg-gray-100 ">Unisex</a>
                        </div>
                        <script>
                            // Show/hide dropdown on mouse enter/leave
                            document.getElementById('cat').addEventListener('mouseenter', function() {
                                const dropdown = this.querySelector('.dropdownbox');
                                dropdown.classList.remove('hidden');
                                dropdown.classList.add('block');
                            });

                            document.getElementById('cat').addEventListener('mouseleave', function() {
                                const dropdown = this.querySelector('.dropdownbox');
                                dropdown.classList.add('hidden');
                                dropdown.classList.remove('block');
                            });
                        </script>
                    </div>
                
                    <a href="saleproduct.php" class="text-slate-700 hover:text-amber-500 transition-colors duration-200 font-medium">SALE</a>
                    <a href="store.php" class="text-slate-700 hover:text-amber-500 transition-colors duration-200 font-medium">Contact</a>
                </nav>
                <?php
                if (isset($_SESSION['user_id'])) {
                    $name = $_SESSION['full_name'];
                    $profile_picture = $_SESSION['profile_picture'];
                    echo "<div class='flex items-center space-around ml-6 space-x-4'>
                        <a href='cart.php' class='text-slate-700 hover:text-amber-500 transition-colors duration-200 relative inline-block'>
                            <img class='rounded-full h-6 w-6' src='shopping-bag.png' alt='cart'>
                            <span class='absolute -top-2 -right-2 bg-amber-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center'>{$_SESSION['cart_count']}</span>
                        </a>
                        <div class='flex items-center ml-2 '>
                        <img src='crudop/images/" . htmlspecialchars($profile_picture) . "' alt='Profile Picture' class='rounded-full h-10 w-10'>
                        <div class='relative group '>
                            <button class='text-slate-700 border-slate-300 px-4 py-2 rounded transition-colors cursor-pointer duration-200 font-medium'>" . htmlspecialchars($name) . "</button>
                            <div class='absolute left-4 top-full mt-0 w-48 bg-white rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-10'>
                                <a href='log_reg/logout.php' class='block px-4 py-4 text-slate-700 hover:text-amber-500 hover:bg-gray-100 rounded-t-lg'>Log Out</a>
                                <a href='log_reg/vieworder.php' class='block px-4 py-4 text-slate-700 hover:text-amber-500 hover:bg-gray-100 rounded-b-lg'>View Order</a>
                            </div>
                        </div>
                    </div>";
                } else {
                    echo "<div class='hidden md:flex items-center space-x-6'>
                        <a href='cart.php' class='text-slate-700 hover:text-amber-500 transition-colors duration-200 relative inline-block'>
                            <img class='rounded-full h-6 w-6' src='shopping-bag.png' alt='cart'>
                        </a>
                        <a href='log_reg/login.php' class='text-slate-700 hover:text-amber-500 transition-colors duration-200'>
                            <img class='rounded-full h-10 w-10' src='login.png' alt='user'>
                        </a>
                    </div>";
                }

              
                ?>

                <!-- Cart and Login Icons -->
                 
                
                <!-- Mobile Menu Button -->
                <div class="md:hidden">
                    <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        const btn = document.getElementById('mobile-menu-button');
                        const menu = document.getElementById('mobile-menu');
                        if (!btn || !menu) return;

                        // prepare menu for animated dropdown
                        menu.style.overflow = 'hidden';
                        menu.style.maxHeight = '0';
                        menu.style.transition = 'max-height 300ms ease';

                        btn.setAttribute('aria-expanded', 'false');

                        btn.addEventListener('click', () => {
                            const isClosed = menu.classList.contains('hidden') || menu.style.maxHeight === '0px' || menu.style.maxHeight === '0';
                            if (isClosed) {
                                // open
                                menu.classList.remove('hidden');
                                // allow browser to compute layout before animating
                                requestAnimationFrame(() => {
                                    menu.style.maxHeight = menu.scrollHeight + 'px';
                                });
                                btn.setAttribute('aria-expanded', 'true');
                            } else {
                                // close
                                menu.style.maxHeight = '0';
                                btn.setAttribute('aria-expanded', 'false');
                                // when animation ends, add hidden so layout is clean
                                const onTransitionEnd = () => {
                                    if (menu.style.maxHeight === '0px' || menu.style.maxHeight === '0') {
                                        menu.classList.add('hidden');
                                    }
                                    menu.removeEventListener('transitionend', onTransitionEnd);
                                };
                                menu.addEventListener('transitionend', onTransitionEnd);
                            }

                            // simple visual toggle on the button (optional)
                            btn.classList.toggle('text-amber-500');
                        });
                    });
                    </script>
                    <button id="mobile-menu-button" class="text-slate-700 hover:text-amber-500 transition-colors duration-200 focus:outline-none">
                        
                    </button>
                </div>
            </div>

            <!-- Mobile Navigation -->
            <div id="mobile-menu" class="md:hidden pb-4 hidden">
                <nav class="flex flex-col space-y-4">
                    <a href="index.html" class="text-slate-700 hover:text-amber-500 transition-colors duration-200 font-medium py-2">Home</a>
                    <a href="product.php" class="text-slate-700 hover:text-amber-500 transition-colors duration-200 font-medium py-2">Featured</a>
                    <!-- To make drop down box while going to catogory tag -->
                     <div id="cow" class="relative group">
                        <button class="text-slate-700  transition-colors cursor-pointer duration-200 font-medium">Categories</button>
                        <div class="absolute hidden left-0 mt-0 w-48 bg-white rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-10">
                            <a href="mensproduct.php" class="block px-4 py-2 text-slate-700 hover:text-amber-500 hover:bg-gray-100 rounded-t-lg">Mens</a>
                            <a href="womensproduct.php" class="block px-4 py-2 text-slate-700 hover:text-amber-500 hover:bg-gray-100">Womens</a>
                            <a href="kidsproduct.php" class="block px-4 py-2 text-slate-700 hover:text-amber-500 hover:bg-gray-100">Kids</a>
                            <a href="unisexproduct.php" class="block px-4 py-2 text-slate-700 hover:text-amber-500 hover:bg-gray-100 rounded-b-lg">Unisex</a>
                        </div>
                        <script>
                            // Show/hide dropdown on mouse enter/leave
                            document.getElementById('cow').addEventListener('mouseenter', function() {
                                const dropdown = this.querySelector('div');
                                dropdown.classList.remove('hidden');
                                dropdown.classList.add('block');
                            });

                            document.getElementById('cow').addEventListener('mouseleave', function() {
                                const dropdown = this.querySelector('div');
                                dropdown.classList.add('hidden');
                                dropdown.classList.remove('block');
                            });
                        </script>
                    </div>
                    <a href="saleproduct.php" class="text-slate-700 hover:text-amber-500 transition-colors duration-200 font-medium py-2">SALE</a>
                    <a href="store.php" class="text-slate-700 hover:text-amber-500 transition-colors duration-200 font-medium py-2">Contact</a>
                </nav>
            </div>
        </div>
    </header>
