<?php
include 'databaseconn.php';


function showProducts($conn, $category = null)
{
    // select products and their brand name (left join in case brand missing)
    $sql = "SELECT product.*, brands.brand_name FROM product LEFT JOIN brands ON product.brand_id = brands.brand_id WHERE product.status = 'active'";

    if ($category !== null) {
        $category = $conn->real_escape_string($category);
        $sql .= " AND category = '$category'";
    }

    $result = $conn->query($sql);

    if ($result->num_rows == 0) {
        echo "<p class='text-center text-gray-500 py-8'>No products found.</p>";
        return;
    }

    echo "<div class='grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6'>";
    
    while ($row = $result->fetch_assoc()) {
        // use correct column name
        $product_id = isset($row['product_id']) ? $row['product_id'] : ($row['id'] ?? '');
        $product_name = htmlspecialchars($row['product_name']);
        $selling_price = number_format($row['selling_price']);
        $image = htmlspecialchars($row['image1']);
        $category = htmlspecialchars($row['category']);
        // prefer readable brand name when available
        $brand = htmlspecialchars($row['brand_name'] ?? $row['brand_id'] ?? '');

        if (empty($row['discount_price'])) {
            echo "<div class='group relative bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2'>
                <div class='relative overflow-hidden h-64'>
                    <img src='crudop/images/$image' alt='$product_name' class='w-full h-full object-cover group-hover:scale-110 transition-transform duration-500'>
                    <div class='absolute inset-0 bg-black/40 group-hover:bg-black/30 transition-colors duration-300'></div>
                    <div class='absolute top-4 left-4 bg-amber-500/90 backdrop-blur-sm p-2 rounded-lg text-sm font-medium text-white'>
                        $brand
                    </div>
                    <div class='absolute bottom-4 right-4 bg-amber-500 text-white px-3 py-1 rounded-full text-sm font-medium'>
                        New
                    </div>
                </div>
                <div class='p-4 flex flex-col'>
                    <p class='text-sm text-amber-600 font-medium mb-1'>$category</p>
                    <h4 class='font-bold text-slate-900 text-lg mb-2 line-clamp-2'>$product_name</h4>
                    <span class='text-xl font-bold text-amber-600'>NRS$selling_price</span>
                    <a href='productdetail.php?id=$product_id' class='w-full bg-slate-900 hover:bg-amber-600 text-white py-3 rounded-lg font-semibold transition-colors duration-300 group-hover:bg-amber-500 mt-4 text-center'>
                        View Details
                    </a>
                </div>
            </div>";
        } else {
            $discount_price = number_format($row['discount_price']);

            echo "<div class='group relative bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2'>
                <div class='relative overflow-hidden h-64'>
                    <img src='crudop/images/$image' alt='$product_name' class='w-full h-full object-cover group-hover:scale-110 transition-transform duration-500'>
                    <div class='absolute inset-0 bg-black/40 group-hover:bg-black/30 transition-colors duration-300'></div>
                    <div class='absolute top-4 left-4 bg-amber-500/90 backdrop-blur-sm p-2 rounded-lg text-sm font-medium text-white'>
                        $brand
                    </div>
                    <div class='absolute bottom-4 right-4 bg-amber-500 text-white px-3 py-1 rounded-full text-sm font-medium'>
                        Sale
                    </div>
                </div>
                <div class='p-4 flex flex-col'>
                    <p class='text-sm text-amber-600 font-medium mb-1'>$category</p>
                    <h4 class='font-bold text-slate-900 text-lg mb-2 line-clamp-2'>$product_name</h4>
                    <div class='flex items-center gap-2 mb-4'>
                        <span class='text-xl font-bold text-amber-600'>NRS$discount_price</span>
                        <span class='text-sm text-slate-400 line-through'>NRS$selling_price</span>
                    </div>
                    <a href='productdetail.php?id=$product_id' class='w-full bg-slate-900 hover:bg-amber-600 text-white py-3 rounded-lg font-semibold transition-colors duration-300 group-hover:bg-amber-500 text-center'>
                        View Details
                    </a>
                </div>
            </div>";
        }
    }

    echo "</div>";
}
          
          // no extra wrapper needed for featured horizontal scroll

function showfeatures($conn, $category = null,$brand=null)
{
    // select products and their brand name (left join in case brand missing)
    $sql = "SELECT product.*, brands.brand_name FROM product LEFT JOIN brands ON product.brand_id = brands.brand_id WHERE product.status = 'active' limit 8";

    if ($category !== null) {
        $category = $conn->real_escape_string($category);
        $sql .= " AND category = '$category'";
    }

    $result = $conn->query($sql);

    if ($result->num_rows == 0) {
        echo "<p class='text-center text-gray-500 py-8'>No products found.</p>";
        return;
    }
    
    while ($row = $result->fetch_assoc()) {
        // use correct column name
        $product_id = isset($row['product_id']) ? $row['product_id'] : ($row['id'] ?? '');
        $product_id = isset($row['product_id']) ? $row['product_id'] : ($row['id'] ?? '');
        $product_name = htmlspecialchars($row['product_name']);
        $selling_price = number_format($row['selling_price']);
        $image = htmlspecialchars($row['image1']);
        $category = htmlspecialchars($row['category']);
        // prefer readable brand name when available
        $brand = htmlspecialchars($row['brand_name'] ?? $row['brand_id'] ?? '');
         if (empty($row['discount_price']))
        {
            // regular price card
            echo"<div class='group relative flex-shrink-0 w-72 md:w-80 snap-start bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2'>
            <div class='relative overflow-hidden h-64'>
                <img src='crudop/images/$image' alt='$product_name' class='w-full h-full object-cover group-hover:scale-110 transition-transform duration-500'>
                <div class='absolute inset-0 bg-black/40 group-hover:bg-black/30 transition-colors duration-300'></div>
                <?php ?>
                <div class='absolute top-4 left-4 bg-amber-500/90 backdrop-blur-sm p-2 rounded-lg text-sm font-medium text-white'>
                    $brand
                </div>
                
                <div class='absolute bottom-4 right-4 bg-amber-500 text-white px-3 py-1 rounded-full text-sm font-medium'>
                    New
                </div>    
                
            </div>
            
            <div class='p-4 flex flex-col '>
                <p class='text-sm text-amber-600 font-medium mb-1'>$category</p>
                <h4 class='font-bold text-slate-900 text-lg mb-2 line-clamp-2'>$product_name</h4>
                
                <span class='text-xl font-bold text-amber-600 '>NRS$selling_price</span>
               
               <a href='productdetail.php?id=$product_id' class='w-full bg-slate-900 hover:bg-amber-600 text-white py-3 rounded-lg font-semibold transition-colors duration-300 group-hover:bg-amber-500 mt-4 text-center'>
                    View Details
                </a>
            </div>
         </div>
         ";
        }
    
            else {
                $discount_price = number_format($row['discount_price']);
      
             
      
              echo "
              <div class='group relative flex-shrink-0 w-72 md:w-80 snap-start bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2'>
                  <div class='relative overflow-hidden h-64'>
                      <img src='crudop/images/$image' alt='$product_name' class='w-full h-full object-cover group-hover:scale-110 transition-transform duration-500'>
                      <div class='absolute inset-0 bg-black/40 group-hover:bg-black/30 transition-colors duration-300'></div>
                      
                      <div class='absolute top-4 left-4 bg-amber-500/90 backdrop-blur-sm p-2 rounded-lg text-sm font-medium text-white'>
                          $brand
                      </div>
                      
                      <div class='absolute bottom-4 right-4 bg-amber-500 text-white px-3 py-1 rounded-full text-sm font-medium'>
                          Sale
                      </div>
                      
                  </div>
                  
                  <div class='p-4 flex flex-col'>
                      <p class='text-sm text-amber-600 font-medium mb-1'>$category</p>
                      <h4 class='font-bold text-slate-900 text-lg mb-2 line-clamp-2'>$product_name</h4>
                      <div class='flex items-center gap-2 mb-4'>
                          <span class='text-xl font-bold text-amber-600'>NRS$discount_price</span>
                          <span class='text-sm text-slate-400 line-through'>NRS$selling_price</span>
                      </div>
                      <a href='productdetail.php?id=$product_id' class='w-full bg-slate-900 hover:bg-amber-600 text-white py-3 rounded-lg font-semibold transition-colors duration-300 group-hover:bg-amber-500 text-center'>
                          View Details
                      </a>
                  </div>
              </div>
              ";
            }
        }
}


function showsaleproduct($conn, $category = null)
{
    // select products with discount price
    $sql = "SELECT product.*, brands.brand_name FROM product LEFT JOIN brands ON product.brand_id = brands.brand_id WHERE product.status = 'active' AND discount_price IS NOT NULL AND discount_price < selling_price";

    if ($category !== null) {
        $category = $conn->real_escape_string($category);
        $sql .= " AND category = '$category'";
    }

    $result = $conn->query($sql);
    
    if ($result->num_rows == 0) {
        echo "<p class='text-center text-gray-500 py-8'>No sale products found.</p>";
        return;
    }
    echo "<div class='grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6'>";
    
    while ($row = $result->fetch_assoc()) {
        // use correct column name
        $product_id = isset($row['product_id']) ? $row['product_id'] : ($row['id'] ?? '');
        $product_name = htmlspecialchars($row['product_name']);
        $selling_price = number_format($row['selling_price']);
        $image = htmlspecialchars($row['image1']);
        // Fallback if image is missing
        $image_path = 'crudop/images/' . $image;
        if (!file_exists(__DIR__ . '/images/' . $image) || empty($image)) {
            $image_path = 'https://via.placeholder.com/300x300?text=No+Image';
        }
        $category_name = htmlspecialchars($row['category']);
        // prefer readable brand name when available
        $brand = htmlspecialchars($row['brand_name'] ?? $row['brand_id'] ?? '');
        $selling_price_val = (float)$row['selling_price'];
        $discount_price_val = (float)$row['discount_price'];
        $discount_percentage = $selling_price_val > 0 ? round((($selling_price_val - $discount_price_val) / $selling_price_val) * 100) : 0;

        if (($row['discount_price']))
        {
            $discount_price = number_format($row['discount_price']);
            echo "
              <div class='group relative bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2'>
                  <div class='relative overflow-hidden h-64'>
                      <img src='{$image_path}' alt='{$product_name}' class='w-full h-full object-cover group-hover:scale-110 transition-transform duration-500'>
                      <div class='absolute inset-0 bg-black/40 group-hover:bg-black/30 transition-colors duration-300'></div>
                      <div class='absolute top-4 left-4 bg-amber-500/90 backdrop-blur-sm p-2 rounded-lg text-sm font-medium text-white'>
                          {$brand}
                      </div>
                      <div class='absolute bottom-4 right-4 bg-amber-500 text-white px-3 py-1 rounded-full text-sm font-medium'>
                          Sale
                      </div>
                  </div>
                  <div class='p-4 flex flex-col'>
                      <p class='text-sm text-amber-600 font-medium mb-1'>{$category_name}</p>
                      <h4 class='font-bold text-slate-900 text-lg mb-2 line-clamp-2'>{$product_name}</h4>
                      <div class='flex items-center gap-2 mb-4'>
                          <span class='text-xl font-bold text-amber-600'>NRS{$discount_price}</span>
                          <span class='text-sm text-slate-400 line-through'>NRS{$selling_price}</span>
                          <span class='text-sm text-white bg-red-500 px-1 py-1 rounded-full'>{$discount_percentage}% Off</span>
                      </div>
                      <a href='productdetail.php?id=$product_id' class='w-full bg-slate-900 hover:bg-amber-600 text-white py-3 rounded-lg font-semibold transition-colors duration-300 group-hover:bg-amber-500 text-center'>
                          View Details
                      </a>
                  </div>
              </div>
              ";
            // Flush output to help debug rendering issues
            
        }
    }
    

          
    echo "</div>"; 

}


function showProductDetail($conn, $product_id)
{
    // ---------- Product Info ----------
    $stmt = $conn->prepare("
        SELECT p.*, b.brand_name
        FROM product p
        LEFT JOIN brands b ON p.brand_id = b.brand_id
        WHERE p.product_id = ?
        LIMIT 1
    ");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();

    if (!$product) {
        echo "<p class='text-center text-red-500'>Product not found.</p>";
        return;
    }

    // ---------- Sizes & Stock ----------
    $sizeStmt = $conn->prepare("
        SELECT s.size_name, ps.size_stock
        FROM product_sizes ps
        JOIN sizes s ON ps.size_id = s.size_id
        WHERE ps.product_id = ?
    ");
    $sizeStmt->bind_param("i", $product_id);
    $sizeStmt->execute();
    $sizes = $sizeStmt->get_result();

    $discountPercent = null;
    if (!empty($product['discount_price'])) {
        $discountPercent = round(
            (($product['selling_price'] - $product['discount_price']) / $product['selling_price']) * 100
        );
    }
    
    echo "
    <script>
    function changeImage(img) {
        document.getElementById('mainImage').src = img.src;
    }
    function validateSize() {
        const sizeSelected = document.querySelector('input[name=\"size\"]:checked');
        if (!sizeSelected) {
            alert('Please select a size before proceeding');
            return false;
        }
        return true;
    }
    </script>
    <a href='javascript:history.back()' class='text-gray-500 hover:text-gray-700 mb-6 inline-block'>
        <img src='crudop/images/arrow.png' alt='Back' class='w-6 h-6 inline-block mr-2'/>     
    </a>
    <div class='max-w-6xl mx-auto bg-white rounded-2xl shadow-lg p-6 md:p-10'>
        <div class='grid grid-cols-1 md:grid-cols-2 gap-12'>
            <!-- IMAGE SECTION -->
            <div>
                <img id='mainImage'
                     src='crudop/images/{$product['image1']}'
                     class='w-full h-[420px] object-cover rounded-xl shadow-md mb-4'>
                <div class='flex gap-4'>
                    <img onclick='changeImage(this)'
                         src='crudop/images/{$product['image1']}'
                         class='w-24 h-24 object-cover rounded cursor-pointer border hover:border-amber-500'>
                    <img onclick='changeImage(this)'
                         src='crudop/images/{$product['image2']}'
                         class='w-24 h-24 object-cover rounded cursor-pointer border hover:border-amber-500'>
                    <img onclick='changeImage(this)'
                         src='crudop/images/{$product['image3']}'
                         class='w-24 h-24 object-cover rounded cursor-pointer border hover:border-amber-500'>
                </div>
            </div>
            <!-- DETAILS SECTION -->
            <div>
                <p class='text-sm text-gray-500 mb-1'>
                    {$product['brand_name']} · {$product['category']}
                </p>
                <h1 class='text-4xl font-extrabold mb-4'>
                    {$product['product_name']}
                </h1>
                <div class='flex items-center gap-4 mb-6'>
    ";

    if ($product['discount_price']) {
        echo "
                    <span class='text-3xl font-bold text-amber-600'>NRS {$product['discount_price']}</span>
                    <span class='line-through text-gray-400'>NRS {$product['selling_price']}</span>
                    <span class='bg-red-500 text-white px-3 py-1 rounded-full text-sm'>{$discountPercent}% OFF</span>
        ";
    } else {
        echo "<span class='text-3xl font-bold'>NRS {$product['selling_price']}</span>";
    }

    echo "
                </div>
                <p class='text-gray-700 leading-relaxed mb-6'>{$product['description']}</p>
                <form method='post' action='add_to_cart.php' onsubmit='return validateSize()' class='flex flex-col gap-4'>
                    <input type='hidden' name='product_id' value='$product_id'>
                    <input type='hidden' name='quantity' value='1'>
                    <input type='hidden' name='product_name' value='" . htmlspecialchars($product['product_name']) . "'>
                    <input type='hidden' name='price' value='" . (!empty($product['discount_price']) ? $product['discount_price'] : $product['selling_price']) . "'>
                    <input type='hidden' name='image' value='" . htmlspecialchars($product['image1']) . "'>
                    <div class='flex justify-between items-center'>
                        <h3 class='font-semibold mb-3'>Select Size</h3>
                        <a href='sizechart.php' class='text-sm text-gray-500 mb-4'>Size chart</a>
                    </div>
                    <div class='grid grid-cols-3 gap-3 mb-8'>
    ";

    while ($size = $sizes->fetch_assoc()) {
        if ($size['size_stock'] > 0) {
            echo "
                        <label class='border rounded-lg px-2 py-2 cursor-pointer hover:border-amber-500'>
                            <input type='radio' name='size' value='{$size['size_name']}' class='hidden'>
                            {$size['size_name']}
                            <span class='block text-xs text-gray-500'>{$size['size_stock']} left</span>
                        </label>
            ";
        } else {
            echo "
                        <div class='border rounded-lg px-2 py-2 opacity-40 cursor-not-allowed'>
                            {$size['size_name']}
                            <span class='block text-xs text-red-500'>Out of stock</span>
                        </div>
            ";
        }
    }

    echo "
                    </div>
                    <div class='flex gap-4'>
                        <button type='submit' class='flex-1 border-2 border-black py-4 rounded-xl text-lg font-semibold hover:bg-amber-100 transition text-center'>
                            Add to Cart
                        </button>
                        <button type='button' onclick='if(validateSize()) window.location.href=\"checkout.php?product_id=$product_id&size=\" + document.querySelector(\"input[name=\\\"size\\\"]:checked\").value;' class='flex-1 border-2 border-black py-4 rounded-xl text-lg font-semibold hover:bg-amber-100 transition text-center'>
                            Buy Now
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    ";
}


function showSimilarProducts($conn, $product_id)
{
    // Fetch the category of the current product
    $stmt = $conn->prepare("SELECT category FROM product WHERE product_id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $currentProduct = $result->fetch_assoc();

    if (!$currentProduct) {
        echo "<p class='text-center text-red-500'>Product not found.</p>";
        return;
    }

    $category = $currentProduct['category'];

    // Fetch similar products in the same category, excluding the current product
    $stmt = $conn->prepare("SELECT * FROM product WHERE category = ? AND product_id != ? AND status = 'active' LIMIT 8");
    $stmt->bind_param("si", $category, $product_id);
    $stmt->execute();
    $similarProducts = $stmt->get_result();

    if ($similarProducts->num_rows == 0) {
        echo "<p class='text-center text-gray-500 py-8'>No similar products found.</p>";
        return;
    }

    while ($row = $similarProducts->fetch_assoc()) {
        // Reuse the product display logic from showProducts function
        // You can call showProducts here or replicate the code as needed
        // For simplicity, we'll just call showProducts with a filter
        showProducts($conn, $category);
        break; // We only need to call it once since it handles multiple products
    }
}

// session_start();

// // Only logged-in users
// function loginRequired() {
//     if (!isset($_SESSION['user_id'])) {
//         header("Location: auth/login.php");
//         exit;
//     }
// }

// // Only admin
// function adminOnly() {
//     if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
//         header("Location: ../log_reg/login.php");
//         exit;
//     }
// }



// ?>


