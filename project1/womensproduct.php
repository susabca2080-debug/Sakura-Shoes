<?php include 'header.php'; ?>
<!-- main -->
 <!-- filter -->
 <main class="min-h-screen bg-gray-100 py-8">
        <div class="container mx-auto px-4">
            <h1 class="text-3xl font-bold text-center mb-8">Women's Products</h1>
            <?php
                include 'crudop/databaseconn.php';
                include 'crudop/function.php';
                showProducts($conn, "Women");
            ?>
        </div>
    </main>
<!-- main -->
<?php include 'footer.php'; ?>