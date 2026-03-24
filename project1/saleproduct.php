


<!-- 
 -->
<?php
// ===============================
// Sale Product Page
// ===============================

include 'crudop/databaseconn.php';
include 'crudop/function.php';
include 'header.php';

// Get category from URL (Men, Women, Kids, Unisex)
$category = $_GET['category'] ?? null;
?>

<main class="bg-gray-100 py-10">

    <!-- ===============================
         Filter Dropdown (Your Style)
         =============================== -->
    <div class="relative group mx-4 px-4 mb-5">
        <span class="mr-2 font-medium">Shop By</span>

        <button
            class="text-slate-700 border border-slate-300 px-4 py-2 rounded
                   hover:bg-gray-100 transition font-medium">
            Categories
        </button>

        <ul class="absolute left-0 top-full mt-1 w-40 bg-white rounded-lg shadow-lg
                   opacity-0 invisible group-hover:opacity-100 group-hover:visible
                   transition-all duration-200 z-10">

            <li>
                <a href="saleproduct.php"
                   class="block px-4 py-2 hover:bg-gray-100 hover:text-amber-500 rounded-t-lg">
                    All Items
                </a>
            </li>

            <li>
                <a href="saleproduct.php?category=Men"
                   class="block px-4 py-2 hover:bg-gray-100 hover:text-amber-500">
                    Men
                </a>
            </li>

            <li>
                <a href="saleproduct.php?category=Women"
                   class="block px-4 py-2 hover:bg-gray-100 hover:text-amber-500">
                    Women
                </a>
            </li>

            <li>
                <a href="saleproduct.php?category=Kids"
                   class="block px-4 py-2 hover:bg-gray-100 hover:text-amber-500">
                    Kids
                </a>
            </li>

            <li>
                <a href="saleproduct.php?category=Unisex"
                   class="block px-4 py-2 hover:bg-gray-100 hover:text-amber-500 rounded-b-lg">
                    Unisex
                </a>
            </li>
        </ul>
    </div>

    <!-- ===============================
         Sale Products Section
         =============================== -->
    <div class="container mx-auto px-4">

        <h1 class="text-3xl font-bold text-center mb-8">
            Sale Products <?= $category ? "- " . htmlspecialchars($category) : "" ?>
        </h1>

        <?php
            // One function call – clean & scalable
            showsaleproduct($conn, $category);
        ?>

    </div>

</main>

<?php include 'footer.php'; ?>