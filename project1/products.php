

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <!-- header -->
    <header>
        <?php include 'header.php'; ?>
    </header>
    <!-- main content -->
    <main class="min-h-screen bg-gray-100 py-8">
        <div class="container mx-auto px-4">
            <h1 class="text-3xl font-bold text-center mb-8">Our Products</h1>
            <?php
                include 'crudop/databaseconn.php';
                include 'crudop/function.php';
                showProducts($conn);
            ?>
        </div>
    </main>
    <!-- footer -->
    <footer>
        <?php include 'footer.php'; ?>
    </footer>
</body>
</html>