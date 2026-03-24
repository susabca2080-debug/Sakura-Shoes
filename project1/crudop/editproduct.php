
<!-- little bit difficult -->
<?php
session_start();
require 'databaseconn.php';

// Get product id from query string
$id = isset($_GET['product_id']) ? (int)$_GET['product_id'] : 0;

if ($id <= 0) {
    echo "Invalid product id.";
    exit;
}

// Fetch existing product
$sql = "SELECT * FROM product WHERE product_id = $id";
$result = $conn->query($sql);

if (!$result || $result->num_rows === 0) {
    echo "Product not found.";
    exit;
}

$product = $result->fetch_assoc();

// Handle form submission (update product)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_name   = $_POST['product_name'] ?? '';
    $category       = $_POST['category'] ?? '';
    $purchase_price = $_POST['purchase_price'] ?? '';
    $selling_price  = $_POST['selling_price'] ?? '';
    $discount_price = $_POST['discount_price'] ?? '';
    $brand_id       = $_POST['brand'] ?? '';
    $description    = $_POST['description'] ?? '';
    $tags           = $_POST['tags'] ?? '';
    $status         = $_POST['status'] ?? 'inactive';

    // Start with existing image filenames
    $image_main = $product['image1'] ?? null; // maps to form field "image"
    $image_1    = $product['image2'] ?? null; // maps to form field "image1"
    $image_2    = $product['image3'] ?? null; // maps to form field "image2"

    // Helper to process optional image upload and return new filename or keep old
    function handle_image_update($field_name, $current_filename)
    {
        if (!isset($_FILES[$field_name]) || $_FILES[$field_name]['error'] !== UPLOAD_ERR_OK) {
            return $current_filename; // no change
        }

        $file = $_FILES[$field_name];
        if (!is_uploaded_file($file['tmp_name'])) {
            return $current_filename;
        }

        $new_name = time() . '-' . basename($file['name']);
        $target   = 'images/' . $new_name;

        if (move_uploaded_file($file['tmp_name'], $target)) {
            return $new_name;
        }

        return $current_filename;
    }

    $image_main = handle_image_update('image', $image_main);
    $image_1    = handle_image_update('image1', $image_1);
    $image_2    = handle_image_update('image2', $image_2);

    // Basic required field check (same idea as addpd.php)
    if (
        $product_name === '' ||
        $category === '' ||
        $purchase_price === '' ||
        $selling_price === '' ||
        $brand_id === '' ||
        $description === '' ||
        $status === ''
    ) {
        $_SESSION['tost'] = ['text' => 'All required fields must be filled', 'type' => 'error'];
    } else {
        $stmt = $conn->prepare("UPDATE product SET product_name = ?, purchase_price = ?, selling_price = ?, discount_price = ?, category = ?, brand_id = ?, description = ?, image1 = ?, image2 = ?, image3 = ?, tags = ?, status = ? WHERE product_id = ?");

        if ($stmt === false) {
            $_SESSION['tost'] = ['text' => 'Prepare failed: ' . $conn->error, 'type' => 'error'];
        } else {
            $stmt->bind_param(
                'sdddsdssssssi',
                $product_name,
                $purchase_price,
                $selling_price,
                $discount_price,
                $category,
                $brand_id,
                $description,
                $image_main,
                $image_1,
                $image_2,
                $tags,
                $status,
                $id
            );

            if ($stmt->execute()) {
                $_SESSION['tost'] = ['text' => 'Product updated successfully', 'type' => 'success'];
                $stmt->close();
                header('Location: dashbord.php');
                exit;
            } else {
                $_SESSION['tost'] = ['text' => 'Update failed: ' . $stmt->error, 'type' => 'error'];
                $stmt->close();
            }
        }

        // Refresh product data after failed update so form shows latest values
        $result = $conn->query($sql);
        if ($result && $result->num_rows > 0) {
            $product = $result->fetch_assoc();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="../output.css">
</head>
<body class="bg-gray-100">
    <div class="max-w-3xl mx-auto p-4 mt-6 bg-white shadow rounded">
        <h1 class="text-2xl font-semibold mb-4">Edit Product #<?php echo htmlspecialchars($product['product_id']); ?></h1>
        <p class="mb-4 text-gray-600">Update the product details below. Fields marked with * are required.</p>

        <form method="post" enctype="multipart/form-data" class="space-y-4">
            <div>
                <label class="block text-sm font-medium mb-1">Product Name *</label>
                <input type="text" name="product_name" required class="w-full mt-1 p-2 border rounded" value="<?php echo htmlspecialchars($product['product_name'] ?? ''); ?>">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Category *</label>
                <select name="category" required class="w-full mt-1 p-2 border rounded">
                    <?php $currentCategory = $product['category'] ?? ''; ?>
                    <option value="">-- Select category --</option>
                    <option value="Men" <?php echo $currentCategory === 'Men' ? 'selected' : ''; ?>>Men</option>
                    <option value="Women" <?php echo $currentCategory === 'Women' ? 'selected' : ''; ?>>Women</option>
                    <option value="Kids" <?php echo $currentCategory === 'Kids' ? 'selected' : ''; ?>>Kids</option>
                    <option value="Unisex" <?php echo $currentCategory === 'Unisex' ? 'selected' : ''; ?>>Unisex</option>
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <div>
                    <label class="block text-sm mb-1">Purchase Price *</label>
                    <input type="number" step="0.01" name="purchase_price" required class="w-full mt-1 p-2 border rounded" value="<?php echo htmlspecialchars($product['purchase_price'] ?? ''); ?>">
                </div>
                <div>
                    <label class="block text-sm mb-1">Selling Price *</label>
                    <input type="number" step="0.01" name="selling_price" required class="w-full mt-1 p-2 border rounded" value="<?php echo htmlspecialchars($product['selling_price'] ?? ''); ?>">
                </div>
                <div>
                    <label class="block text-sm mb-1">Discount Price</label>
                    <input type="number" step="0.01" name="discount_price" class="w-full mt-1 p-2 border rounded" value="<?php echo htmlspecialchars($product['discount_price'] ?? ''); ?>">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Brand *</label>
                <select name="brand" required class="w-full mt-1 p-2 border rounded">
                    <option value="">-- Select Brand --</option>
                    <?php
                    $currentBrand = $product['brand_id'] ?? '';
                    $brandResult = $conn->query("SELECT brand_id, brand_name FROM brands");
                    if ($brandResult) {
                        while ($row = $brandResult->fetch_assoc()) {
                            $selected = ($row['brand_id'] == $currentBrand) ? 'selected' : '';
                            echo "<option value='{$row['brand_id']}' {$selected}>{$row['brand_name']}</option>";
                        }
                    }
                    ?>
                </select>
            </div>

            <div>
                <label class="block text-sm mb-1">Description *</label>
                <textarea name="description" rows="4" class="w-full mt-1 p-2 border rounded" required><?php echo htmlspecialchars($product['description'] ?? ''); ?></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <div class="border rounded p-2">
                    <label class="block text-sm mb-1">Main Image</label>
                    <?php if (!empty($product['image1'])): ?>
                        <img src="images/<?php echo htmlspecialchars($product['image1']); ?>" alt="Current image" class="h-20 w-20 object-cover mb-2 rounded">
                    <?php endif; ?>
                    <input type="file" name="image" accept="image/*">
                </div>
                <div class="border rounded p-2">
                    <label class="block text-sm mb-1">Image 1</label>
                    <?php if (!empty($product['image2'])): ?>
                        <img src="images/<?php echo htmlspecialchars($product['image2']); ?>" alt="Current image" class="h-20 w-20 object-cover mb-2 rounded">
                    <?php endif; ?>
                    <input type="file" name="image1" accept="image/*">
                </div>
                <div class="border rounded p-2">
                    <label class="block text-sm mb-1">Image 2</label>
                    <?php if (!empty($product['image3'])): ?>
                        <img src="images/<?php echo htmlspecialchars($product['image3']); ?>" alt="Current image" class="h-20 w-20 object-cover mb-2 rounded">
                    <?php endif; ?>
                    <input type="file" name="image2" accept="image/*">
                </div>
            </div>

            <div>
                <label class="block text-sm mb-1">Tags (comma separated)</label>
                <input type="text" name="tags" class="w-full mt-1 p-2 border rounded" value="<?php echo htmlspecialchars($product['tags'] ?? ''); ?>">
            </div>

            <div>
                <label class="block text-sm mb-1">Status *</label>
                <?php $currentStatus = $product['status'] ?? 'inactive'; ?>
                <select name="status" class="w-full mt-1 p-2 border rounded">
                    <option value="active" <?php echo $currentStatus === 'active' ? 'selected' : ''; ?>>Active</option>
                    <option value="inactive" <?php echo $currentStatus === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                </select>
            </div>

            <div class="flex items-center gap-3 pt-4">
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">Update Product</button>
                <a href="dashbord.php" class="text-sm text-gray-600">Back to Dashboard</a>
            </div>
        </form>
    </div>
</body>
</html>
