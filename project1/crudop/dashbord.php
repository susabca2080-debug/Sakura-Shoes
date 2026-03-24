
<?php session_start();
session_regenerate_id(true);
/**
 * Implements an inactivity-based session timeout mechanism.
 *
 * This logic enforces a maximum period of user inactivity (30 minutes by
 * default, defined in seconds by the `$timeout` variable). On each request
 */
$timeout = 1800; // 30 minutes

if(isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeout){
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit;
}

$_SESSION['LAST_ACTIVITY'] = time();

// Include database connection
require_once 'databaseconn.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Store Dashboard • Sakura Shoes</title>
  <link rel="stylesheet" href="../output.css">
  <script src="https://unpkg.com/lucide@0.254.0/dist/lucide.min.js"></script>
  <!-- to use tostify function -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
  <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
  <!-- using toastify to display notifications -->
  <?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (isset($_SESSION['tost'])) {
    $t = $_SESSION['tost'];
    $text = addslashes($t['text']);
    $type = ($t['type'] ?? 'success');
    unset($_SESSION['tost']);
    $bg = $type === 'success'
        ? "linear-gradient(90deg, #00b09b, #96c93d)"
        : "linear-gradient(90deg, #ff5f6d, #ffc371)";

    echo "<script>
    document.addEventListener('DOMContentLoaded', function(){
      Toastify({
        text: \"{$text}\",
        duration: 5000,
        close: true,
        gravity: 'top',
        position: 'right',
        style: { background: '{$bg}', color: '#fff' }
      }).showToast();
    });
    </script>";
}
// end toastify
// include'function.php';
// adminonly();

?>

</head>

 <body class="bg-slate-50">
  <!-- Header -->
  <header class="bg-white border-b border-slate-200 sticky top-0 z-40 shadow-sm">
    <div class="flex items-center justify-between px-6 py-4">
      <!-- Logo & Brand -->
      <div class="flex items-center gap-3">
        <img src=" ../Untitleddesign.png" alt="Sakura Shoes" class="h-10 w-10 rounded-full border-2 border-amber-400 shadow-sm">
        <div>
          <h1 class="text-lg font-bold text-red-400">Sakura Shoes Center</h1>
          <p class="text-xs text-slate-500">Store • Admin Panel</p>
        </div>
      </div>

      <!-- Welcome Message -->
      <div class="hidden md:block">
        <p class="text-sm text-slate-600">Welcome back, <span class="font-semibold text-slate-800"><?php echo $_SESSION['full_name'] ?? 'Admin'; ?></span>! 👋</p>
      </div>

      <!-- Profile -->
      <div class="flex items-center gap-3">
        <img src="<?php echo $_SESSION['profile_picture'] ?? 'default-profile.png'; ?>" alt="Admin Profile" class="h-10 w-10 rounded-full border-2 border-amber-400 shadow-sm">
        <div class="hidden sm:block">
          <p class="text-sm font-medium text-slate-800"><?php echo $_SESSION['full_name'] ?? 'Admin'; ?></p>
          <p class="text-xs text-slate-500">Admin</p>
        </div>
      </div>
    </div>
  </header>

  <div class="flex min-h-screen bg-gray-100">
  <!-- Sticky Sidebar -->
  <aside
    class="hidden md:flex flex-col w-64 bg-gray-900 text-white sticky top-20 max-h-[calc(100vh-5rem)] overflow-auto">
    <div class="p-6 text-2xl font-bold border-b border-gray-700">Admin Panel</div>
    <nav class="mt-6 flex-1">
      <ul class="space-y-2">
        <li class="px-6 py-3 hover:bg-gray-700 cursor-pointer transition-colors rounded"
          onclick="showSection('dashboard')">DashBoard</li>
        <li>  <details class=" adminsection px-6 py-3 rounded  " onclick="event.stopPropagation()">
              <summary class="cursor-pointer select-none text-white">Products</summary>
              <ul class="mt-2 ml-4 space-y-1">
                <li class="block px-4 py-2 rounded hover:bg-gray-700" onclick="showSection('productadd')">add product</li>
                <li class="block px-4 py-2 rounded hover:bg-gray-700" onclick="showSection('productview')">view products</li>
                <li class="block px-4 py-2 rounded hover:bg-gray-700" onclick="showSection('productedit')">edit products</li>
              </ul>
            </details>
        </li>
        <li><details class=" adminsection px-6 py-3 rounded  " onclick="event.stopPropagation()">
              <summary class="cursor-pointer select-none text-white">Manage Size & Stock</summary>
              <ul class="mt-2 ml-4 space-y-1">
                <li class="block px-4 py-2 rounded hover:bg-gray-700" onclick="showSection('sizeadd')">Add size</li>
                <li class="block px-4 py-2 rounded hover:bg-gray-700" onclick="showSection('productsize')">Add stock</li>
                <li class="block px-4 py-2 rounded hover:bg-gray-700" onclick="showSection('viewsize')">view size</li>
                <li class="block px-4 py-2 rounded hover:bg-gray-700" onclick="showSection('viewstock')">View stock</li>
                <li class="block px-4 py-2 rounded hover:bg-gray-700" onclick="showSection('editstock')">edit stock</li>
              </ul>
            </details>
        </li>
        <li><details class=" adminsection px-6 py-3 rounded  " onclick="event.stopPropagation()">
              <summary class="cursor-pointer select-none text-white">brand</summary>
              <ul class="mt-2 ml-4 space-y-1">
                <li class="block px-4 py-2 rounded hover:bg-gray-700" onclick="showSection('brandadd')">add brand</li>
                <li class="block px-4 py-2 rounded hover:bg-gray-700" onclick="showSection('brandview')">view brand</li>
              </ul>
            </details>
          </li>
        <li class="px-6 py-3 hover:bg-gray-700 cursor-pointer transition-colors rounded" onclick="showSection('orders')">View Orders</li>
        <li class="px-6 py-3 hover:bg-gray-700 cursor-pointer transition-colors rounded" onclick="showSection('users')">Users</li>
      </ul>
    </nav>
  </aside>
  <!-- Main Content -->
  <main class="flex-1 p-6 max-h-[calc(100vh-5rem)] overflow-auto">
   <!-- 1 dashboard section -->
      <div id="dashboard" class="admin-section">
        <div class="space-y-6 space-x-5 ">
          <!-- Header -->
          <div>
            <h1 class="text-4xl font-bold text-gray-900">Dashboard</h1>
            <p class="mt-2 text-gray-600">Real-time overview of your store performance</p>
          </div>

          <!-- Primary Stats Grid -->
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Total Users -->
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-lg shadow-md border-l-4 border-blue-600">
              <div class="flex justify-between items-start">
                <div>
                  <p class="text-sm font-semibold text-blue-600 uppercase tracking-wide">Total Users</p>
                  <p class="text-4xl font-bold text-blue-900 mt-2">
                    <?php
                    $user_sql = "SELECT COUNT(*) as count FROM users WHERE role = 'customer'";
                    $user_result = $conn->query($user_sql);
                    $user_count = 0;
                    if ($user_result && $row = $user_result->fetch_assoc()) {
                      $user_count = $row['count'];
                    }
                    echo $user_count;
                    ?>
                  </p>
                </div>
                <div class="bg-blue-200 p-3 rounded-full"><i data-lucide="users" class="text-blue-600"></i></div>
              </div>
              <p class="text-xs text-blue-600 mt-3">Active customers</p>
            </div>

            <!-- Total Products -->
            <div class="bg-gradient-to-br from-green-50 to-green-100 p-6 rounded-lg shadow-md border-l-4 border-green-600">
              <div class="flex justify-between items-start">
                <div>
                  <p class="text-sm font-semibold text-green-600 uppercase tracking-wide">Total Products</p>
                  <p class="text-4xl font-bold text-green-900 mt-2">
                    <?php
                    $prod_sql = "SELECT COUNT(*) as count FROM product";
                    $prod_result = $conn->query($prod_sql);
                    $prod_count = 0;
                    if ($prod_result && $row = $prod_result->fetch_assoc()) {
                      $prod_count = $row['count'];
                    }
                    echo $prod_count;
                    ?>
                  </p>
                </div>
                <div class="bg-green-200 p-3 rounded-full"><i data-lucide="package" class="text-green-600"></i></div>
              </div>
              <p class="text-xs text-green-600 mt-3">In catalog</p>
            </div>

            <!-- Total Orders -->
            <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-6 rounded-lg shadow-md border-l-4 border-purple-600">
              <div class="flex justify-between items-start">
                <div>
                  <p class="text-sm font-semibold text-purple-600 uppercase tracking-wide">Total Orders</p>
                  <p class="text-4xl font-bold text-purple-900 mt-2">
                    <?php
                    $order_sql = "SELECT COUNT(*) as count FROM orders";
                    $order_result = $conn->query($order_sql);
                    $order_count = 0;
                    if ($order_result && $row = $order_result->fetch_assoc()) {
                      $order_count = $row['count'];
                    }
                    echo $order_count;
                    ?>
                  </p>
                </div>
                <div class="bg-purple-200 p-3 rounded-full"><i data-lucide="shopping-cart" class="text-purple-600"></i></div>
              </div>
              <p class="text-xs text-purple-600 mt-3">All time</p>
            </div>

            <!-- Total Revenue -->
            <div class="bg-gradient-to-br from-orange-50 to-orange-100 p-6 rounded-lg shadow-md border-l-4 border-orange-600">
              <div class="flex justify-between items-start">
                <div>
                  <p class="text-sm font-semibold text-orange-600 uppercase tracking-wide">Total Revenue</p>
                  <p class="text-4xl font-bold text-orange-900 mt-2">
                    Rs<?php
                    // Match the column name in the orders table (order_status)
                    $revenue_sql = "SELECT SUM(total_amount) as total FROM orders WHERE order_status = 'completed'";
                    $revenue_result = $conn->query($revenue_sql);
                    $revenue = 0;
                    if ($revenue_result && $row = $revenue_result->fetch_assoc()) {
                      $revenue = (float)$row['total'];
                    }
                    echo number_format($revenue, 2);
                    ?>
                  </p>
                </div>
                <div class="bg-orange-200 p-3 rounded-full"><i data-lucide="trending-up" class="text-orange-600"></i></div>
              </div>
              <p class="text-xs text-orange-600 mt-3">Completed orders</p>
            </div>
          </div>

          <!-- Secondary Stats -->
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Total Cost -->
            <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow">
              <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-gray-700">Total Inventory Cost</h3>
                <div class="bg-red-100 p-2 rounded-full"><i data-lucide="tag" class="text-red-600 w-5 h-5"></i></div>
              </div>
              <p class="text-3xl font-bold text-red-600">
                Rs<?php
                $cost_sql = "SELECT SUM(p.purchase_price * ps.size_stock) as total_cost FROM product p JOIN product_sizes ps ON p.product_id = ps.product_id";
                $cost_result = $conn->query($cost_sql);
                $cost = 0;
                if ($cost_result && $row = $cost_result->fetch_assoc()) {
                  $cost = (float)$row['total_cost'];
                }
                echo number_format($cost, 2);
                ?>
              </p>
              <p class="text-xs text-gray-500 mt-2">Current stock value</p>
            </div>

            <!-- Profit/Loss -->
            <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow">
              <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-gray-700">Profit/Loss</h3>
                <div class="bg-green-100 p-2 rounded-full"><i data-lucide="bar-chart-3" class="text-green-600 w-5 h-5"></i></div>
              </div>
              <p class="text-3xl font-bold" style="color: <?php echo ($revenue - $cost) >= 0 ? '#16a34a' : '#dc2626'; ?>">
                Rs<?php echo number_format($revenue - $cost, 2); ?>
              </p>
              <p class="text-xs text-gray-500 mt-2"><?php echo ($revenue - $cost) >= 0 ? 'Positive growth' : 'Needs attention'; ?></p>
            </div>

            <!-- Low Stock Alert -->
            <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow border-l-4 border-yellow-500">
              <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-gray-700">Low Stock Items</h3>
                <div class="bg-yellow-100 p-2 rounded-full"><i data-lucide="alert-triangle" class="text-yellow-600 w-5 h-5"></i></div>
              </div>
              <p class="text-3xl font-bold text-yellow-600">
                <?php
                $low_stock_sql = "SELECT COUNT(*) as count FROM product_sizes WHERE size_stock < 10";
                $low_stock_result = $conn->query($low_stock_sql);
                $low_stock = 0;
                if ($low_stock_result && $row = $low_stock_result->fetch_assoc()) {
                  $low_stock = $row['count'];
                }
                echo $low_stock;
                ?>
              </p>
              <p class="text-xs text-gray-500 mt-2">Reorder soon</p>
            </div>
          </div>

          <!-- Category Breakdown -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-white p-6 rounded-lg shadow-md">
              <h3 class="text-lg font-semibold text-gray-800 mb-4">Products by Category</h3>
              <div class="space-y-3">
                <?php
                $categories = ['Men', 'Women', 'Kids', 'Unisex'];
                foreach ($categories as $cat) {
                  $cat_sql = "SELECT COUNT(*) as count FROM product WHERE category = '$cat'";
                  $cat_result = $conn->query($cat_sql);
                  $cat_count = 0;
                  if ($cat_result && $row = $cat_result->fetch_assoc()) {
                    $cat_count = $row['count'];
                  }
                  $percentage = $prod_count ? round(($cat_count / $prod_count) * 100) : 0;
                ?>
                <div>
                  <div class="flex justify-between text-sm mb-1">
                    <span class="font-medium text-gray-700"><?php echo $cat; ?></span>
                    <span class="text-gray-600"><?php echo $cat_count; ?> (<?php echo $percentage; ?>%)</span>
                  </div>
                  <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-blue-500 h-2 rounded-full" style="width: <?php echo $percentage; ?>%"></div>
                  </div>
                </div>
                <?php } ?>
              </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md">
              <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>
              <div class="space-y-2">
                <button onclick="showSection('productadd')" class="w-full bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded transition">+ Add New Product</button>
                <button onclick="showSection('sizeadd')" class="w-full bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded transition">+ Add Size</button>
                <button onclick="showSection('brandadd')" class="w-full bg-purple-500 hover:bg-purple-600 text-white py-2 px-4 rounded transition">+ Add Brand</button>
                <button onclick="showSection('orders')" class="w-full bg-orange-500 hover:bg-orange-600 text-white py-2 px-4 rounded transition">View Orders</button>
              </div>
            </div>
          </div>

        </div>
      </div>
      <!-- dashboardsection ends -->

   <!-- 2 Add Product Form (centered only within this wrapper) -->
    <div id="productadd" class="admin-section items-center justify-center w-full min-h-[calc(100vh-5rem)] hidden">
      <div class="w-full max-w-3xl mx-auto p-4">
        <h2 class="text-2xl font-semibold mb-4">Add / Edit Product</h2>

        
        <form method="post" action="addpd.php" enctype="multipart/form-data" class="bg-white p-6 rounded shadow space-y-4">
          <!--  -->

          <label class="block">
            <span class="text-sm font-medium">Product Name</span>
            <input name="product_name" required class="w-full mt-1 p-2 border rounded" value="<?php echo htmlspecialchars($product['product_name'] ?? ''); ?>">
          </label>

          <label class="block">
            <span class="text-sm font-medium">Category</span>
            <select name="category" required class="w-full mt-1 p-2 border rounded">
              <option value="">-- Select category --</option>
              <option value="Men">Men</option>
              <option value="Women">Women</option>
              <option value="Kids">Kids</option>
              <option value="Unisex">Unisex</option>
            </select>
          </label>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <label class="block">
              <span class="text-sm">Purchase Price</span>
              <input type="number" step="0.01" name="purchase_price"  required class="w-full mt-1 p-2 border rounded " >
            </label>
            <label class="block">
              <span class="text-sm">Selling Price</span>
              <input type="number" step="0.01" name="selling_price" required class = "w-full mt-1 p-2 border rounded ">
            </label>
            <label class="block">
              <span class="text-sm">Discount Price</span>
              <input type="number" step="0.01" name="discount_price" class="w-full mt-1 p-2 border rounded" >
            </label>
          </div>
           <!-- featch brand and show option -->
          <label class="block">
            <span class="text-sm font-medium">Brand</span>
            <select name="brand" required class="w-full mt-1 p-2 border rounded ">
              <option value="">-- Select Brand --</option>
               <?php
              include 'databaseconn.php';
              $sql = "SELECT brand_id, brand_name FROM brands";
              $result = $conn->query("SELECT brand_id, brand_name FROM brands");
              while($row = $result->fetch_assoc()){
                  echo "<option value='{$row['brand_id']}'>{$row['brand_name']}</option>";
              }
              ?>
            </select>
          </label>

          <label class="block">
            <span class="text-sm">Description</span>
            <textarea name="description" rows="5" class="w-full mt-1 p-2 border rounded"></textarea>
          </label>
           <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            
          <label class="block border rounded">
            <span class="text-sm required:">Image</span>
           
            <input type="file" required name="image" accept="image/*">
          </label>
          <label class="block border rounded">
          <span class="text-sm">Image1</span>
          <input type="file" name="image1" accept="image/*">
          </label>
          <label class="block border rounded">
          <span class="text-sm">Image2</span>
          <input type="file" name="image2" accept="image/*">
          </label>
            </div>


          <label class="block">
            <span class="text-sm">Tags (comma separated)</span>
            <input name="tags" class="w-full mt-1 p-2 border rounded" value="">
          </label>

          <label class="block">
            <span class="text-sm">Status</span>
            <select name="status" class="w-full mt-1 p-2 border rounded">
              <option value="active" >Active</option>
              <option value="inactive" >Inactive</option>
            </select>
          </label>

          <div class="flex items-center gap-3">
            <button class="bg-red-500 text-white px-4 py-2 rounded">Add Product</button>
            <button type="button" onclick="showSection('dashboard')" class="text-sm text-gray-600">Back to dashboard</button>
          </div>
        </form>
      </div>
        </div>
    <!-- Add Product Form Ends-->
       
   <!-- 3`Add size only form -->

     <div id="sizeadd" class="admin-section items-center justify-center w-full min-h-[calc(100vh-5rem)] hidden">
      <div class="w-full max-w-3xl mx-auto p-4">
        <h2 class="text-2xl font-semibold mb-4">Add Size</h2>

        
        <form method="post" action="addsize.php" enctype="multipart/form-data" class="bg-white p-6 rounded shadow space-y-4">
          <!--  -->

          
          <label for="size_name" class="block">Enter Size </label> 
          <input type="text" name="size_name" id="size_name" class="w-full mt-1 p-2 border rounded" required>
          
          <div class="flex items-center gap-3">
            <button class="bg-red-500 text-white px-4 py-2 rounded">Add Size</button>
            <button type="button" onclick="showSection('dashboard')" class="text-sm text-gray-600">Back to dashboard</button>
          </div>
        </form>
      </div>
      </div>
    
    <!-- Add size only ends-->
    <!--ADD PRODUCT SIZE AND STOCK   -->
      <div id="productsize" class="admin-section items-center justify-center w-full min-h-[calc(100vh-5rem)] hidden">
        <div class="w-full max-w-3xl mx-auto p-4">
          <h2 class="text-2xl font-semibold mb-4">Add Product Size and Stock</h2>
          
          <form method="POST" action="addproductsize.php" enctype="multipart/form-data" class="bg-white p-6 rounded shadow space-y-4">

            <label for="product_id" class="block">Select Product </label>
            <select name="product_id" id="product_id" class="w-full mt-1 p-2 border rounded" required>
              <option value="">-- Select Product --</option>
              <?php
              include 'databaseconn.php';
              $sql = "SELECT product_id, product_name FROM product";
              $result = $conn->query($sql);
              while ($row = $result->fetch_assoc()) {
                  echo "<option value='" . $row['product_id'] . "'>" . htmlspecialchars($row['product_name']) . "</option>";
              }
              ?>
            </select>

            
            <label class="block">Select Sizes and enter stock</label>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 max-h-48 overflow-auto border rounded p-5">
              <?php
               $sql = "SELECT size_id, size_name FROM sizes ORDER BY size_name";
               $result = $conn->query($sql);
               while ($row = $result->fetch_assoc()) {
                 $sid = (int)$row['size_id'];
                 $sname = htmlspecialchars($row['size_name']);
                 echo "<label class='flex items-center gap-2'><input type='checkbox' name='size_id[]' value='{$sid}' class='size-checkbox'> <span>{$sname}</span> <input type='number' name='stock_quantity[{$sid}]' value='0' min='0' class='stock-input w-24 p-1 border rounded'></label>";
               }
              ?>
            </div>

            <div class="flex items-center gap-3">
              <button  type="submit" class="bg-red-500 text-white px-4 py-2 rounded" id="add-size-btn">Add Size and Stock</button>
              <button type="button" onclick="showSection('dashboard')" class="text-sm text-gray-600">Back to dashboard</button>
           </div>
          </form>
        </div>
      </div>
      <!--ADD PRODUCT SIZE AND STOCK ENDS  -->
  <!-- 4 add brand form -->
  <div id="brandadd" class="admin-section items-center justify-center w-full min-h-[calc(100vh-5rem)] hidden">
    <div class="w-full max-w-3xl mx-auto p-4">
      <h2 class="text-2xl font-semibold mb-4">Add Brand</h2>
      <form method="post" action="addbrand.php" enctype="multipart/form-data" class="bg-white p-6 rounded shadow space-y-4">
        <label for="brand_name" class="block">
          <span class="text-sm font-medium">Enter Brand Name</span>
          <input type="text" name="brand_name" id="brand_name" required class="w-full mt-1 p-2 border rounded" required>
        </label>

        <label for="status" class="block">
          <span class="text-sm font-medium">Enter Status</span>
          <select name="status" id="status" class="w-full mt-1 p-2 border rounded" required>
            <option value="">-- Select Status --</option>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
          </select>
        </label>

        <div class="flex items-center gap-3">
          <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">Add Brand</button>
          <button type="button" onclick="showSection('dashboard')" class="text-sm text-gray-600">Back to dashboard</button>
        </div>
      </form>
    </div>
  </div>
  <!-- add brand ends-->

     <!-- 5 viewsbrand-->
  <div id="brandview" class="admin-section items-center justify-center w-full min-h-[calc(100vh-5rem)] hidden">
      <div class="w-full max-w-3xl mx-auto p-4">
        <h2 class="text-2xl font-semibold mb-4">View Brand</h2>
        <!-- Content for viewing sizes goes here -->
        <div class="bg-white p-6 rounded shadow space-y-4">
          <table class="w-full table-auto border-collapse border border-gray-300">
            <thead>
              <tr class="bg-gray-200">
                <th class="border border-gray-300 px-4 py-2">Brand ID</th>
                <th class="border border-gray-300 px-4 py-2">Brand Name</th>
                <th class="border border-gray-300 px-4 py-2">products</th>
                <th class="border border-gray-300 px-4 py-2">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php
              include 'databaseconn.php';
              $sql = "SELECT brand_id, brand_name FROM brands ORDER BY brand_name";

              $result = $conn->query($sql);
              if ($result) {
                  while ($row = $result->fetch_assoc()) {
                      // count the products in this brand
                      $product_count = 0;
                      $product_count_sql = "SELECT COUNT(*) as count FROM product WHERE brand_id = " . (int)$row['brand_id'];
                      $product_count_result = $conn->query($product_count_sql);
                      if ($product_count_result && $count_row = $product_count_result->fetch_assoc()) {
                          $product_count = (int)$count_row['count'];
                      }

                      echo "<tr>";
                      echo "<td class='border border-gray-300 px-4 py-2'>" . htmlspecialchars($row['brand_id']) . "</td>";
                      echo "<td class='border border-gray-300 px-4 py-2'>" . htmlspecialchars($row['brand_name']) . "</td>";
                      echo "<td class='border border-gray-300 px-4 py-2'>" . $product_count . "</td>";
                      echo "<td class='border border-gray-300 px-4 py-2'>
                      <form method='post' action='deletebrand.php' onsubmit=\"return confirm('Are you sure you want to delete this brand?');\">
                              <button class='bg-red-500 text-white px-3 py-1 rounded flex content-center cursor-pointer'>Delete</button>
                              <input type='hidden' name='brand_id' value='" . htmlspecialchars($row['brand_id']) . "'>  
                          </form>
                            </td>";
                      echo "</tr>";
                  }
              } else {
                  echo "<tr><td colspan='4' class='border border-gray-300 px-4 py-2 text-center text-red-500'>Error fetching brands: " . htmlspecialchars($conn->error) . "</td></tr>";
              }
              ?>
            </tbody>
          </table>
      </div>
    </div>
  </div>
    <!-- view brand ends-->

    <!-- 6 view size -->
    <div id="viewsize" class="admin-section items-center justify-center w-full min-h-[calc(100vh-5rem)] hidden">
      <div class="w-full max-w-3xl mx-auto p-4">
        <h2 class="text-2xl font-semibold mb-4">View Size</h2>
        <!-- Content for viewing sizes goes here -->
        <div class="bg-white p-6 rounded shadow space-y-4">
          <table class="w-full table-auto border-collapse border border-gray-300">
            <thead>
              <tr class="bg-gray-200">
                <th class="border border-gray-300 px-4 py-2">Size ID</th>
                <th class="border border-gray-300 px-4 py-2">Size Name</th>
                <th class="border border-gray-300 px-4 py-2">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php
              require_once 'databaseconn.php';
              $sql = "SELECT size_id, size_name FROM sizes ORDER BY size_name";

              if (!isset($conn) || $conn->connect_error) {
                  $err = isset($conn) ? $conn->connect_error : 'Connection not initialized';
                  echo "<tr><td colspan='2' class='border border-gray-300 px-4 py-2 text-center text-red-500'>DB error: " . htmlspecialchars($err) . "</td></tr>";
              } else {
                  $result = $conn->query($sql);

                  if (!$result) {
                      echo "<tr><td colspan='2' class='border border-gray-300 px-4 py-2 text-center text-red-500'>Error fetching sizes: " . htmlspecialchars($conn->error) . "</td></tr>";
                  } elseif ($result->num_rows === 0) {
                      echo "<tr><td colspan='2' class='border border-gray-300 px-4 py-2 text-center text-gray-500'>No sizes found.</td></tr>";
                  } else {
                      while ($row = $result->fetch_assoc()) {
                          echo "<tr>";
                          echo "<td class='border border-gray-300 px-4 py-2'>" . htmlspecialchars($row['size_id']) . "</td>";
                          echo "<td class='border border-gray-300 px-4 py-2'>" . htmlspecialchars($row['size_name']) . "</td>";
                          echo "<td class='border w-4  border-gray-300 px-4 py-2'>
                          <form method='post' action='deletesize.php' onsubmit=\"return confirm('Are you sure you want to delete this size?');\">
                              <button type='submit' class='bg-red-500 text-white px-3 py-1 rounded flex content-center cursor-pointer'>Delete</button>
                              <input type='hidden' name='size_id' value='" . htmlspecialchars($row['size_id']) . "'>
                          </form>
                            </td>";
                          echo "</tr>";
                      }
                  }
              }
              ?>
            </tbody>
          </table>
         
        </div>
      </div>
    </div>
    <!-- view size ends-->

      <!-- 7 product view -->
   <div id="productview" class="admin-section items-center justify-center w-full min-h-[calc(100vh-5rem)] hidden">
      <div class="w-full max-w-3xl mx-auto p-4">
        <h2 class="text-2xl font-semibold mb-4">View Products</h2>
          
           <div class="bg-white p-6 rounded shadow space-y-4">
          <table class="w-full table-auto border-collapse border border-gray-300">
            <thead>
              <tr class="bg-gray-200">
                <th class="border border-gray-300 px-4 py-2">Product ID</th>
                <th class="border border-gray-300 px-4 py-2">Product Name</th>
                <th class="border border-gray-300 px-4 py-2">Category</th>
                <th class="border border-gray-300 px-4 py-2">Brand</th>
                <th class="border border-gray-300 px-4 py-2">Price</th>
                <th class="border border-gray-300 px-4 py-2">Status</th>
                <th class="border border-gray-300 px-4 py-2">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php

              require_once 'databaseconn.php';

              // Extra safety: check connection object
              if (!isset($conn) || $conn->connect_error) {
                  $err = isset($conn) ? $conn->connect_error : 'Connection object not set';
                  echo "<tr><td colspan='7' class='border border-gray-300 px-4 py-2 text-center text-red-500'>DB connection error: " . htmlspecialchars($err) . "</td></tr>";
              } else {
                  $sql = "SELECT p.product_id, p.product_name, p.category, COALESCE(b.brand_name, 'No brand') AS brand_name, p.selling_price, p.status 
                          FROM product p 
                          LEFT JOIN brands b ON p.brand_id = b.brand_id 
                          ORDER BY p.product_name";

                  $result = $conn->query($sql);

                  if (!$result) {
                      echo "<tr><td colspan='7' class='border border-gray-300 px-4 py-2 text-center text-red-500'>Error fetching products: " . htmlspecialchars($conn->error) . "</td></tr>";
                  } elseif ($result->num_rows === 0) {
                      echo "<tr><td colspan='7' class='border border-gray-300 px-4 py-2 text-center text-gray-500'>No products found.</td></tr>";
                  } else {
                      // Replace the initial debug row with real data when results exist
                      // (We already printed one debug row above; it's fine for now.)
                      while ($row = $result->fetch_assoc()) {
                          echo "<tr>";
                          echo "<td class='border border-gray-300 px-4 py-2'>" . htmlspecialchars($row['product_id']) . "</td>";
                          echo "<td class='border border-gray-300 px-4 py-2'>" . htmlspecialchars($row['product_name']) . "</td>";
                          echo "<td class='border border-gray-300 px-4 py-2'>" . htmlspecialchars($row['category']) . "</td>";
                          echo "<td class='border border-gray-300 px-4 py-2'>" . htmlspecialchars($row['brand_name']) . "</td>";
                          echo "<td class='border border-gray-300 px-4 py-2'>(rs)" . number_format((float)$row['selling_price'], 2) . "</td>";
                          echo "<td class='border border-gray-300 px-4 py-2'>" . htmlspecialchars($row['status']) . "</td>";
                          echo "<td class='border border-gray-300 px-4 py-2'>
                              <a href='editproduct.php?product_id=" . htmlspecialchars($row['product_id']) . "'><button  onclick=\"return confirm('Are you sure you want to edit this product?')\" class='bg-yellow-500 text-white px-3 py-1 rounded mr-2 cursor-pointer'>Edit</button></a>
                              <a href='deleteproduct.php?product_id=" . htmlspecialchars($row['product_id']) . "'><button  onclick=\"return confirm('Are you sure you want to delete this product?')\" class='bg-red-500 text-white px-3 py-1 rounded cursor-pointer'>Delete</button></a>
                            </td>";
                          echo "</tr>";
                      }
                  }
              }
              ?>
            </tbody>
          </table> 
           <!-- productcount and balance -->
           <div class="mt-6 p-4 bg-gray-100 rounded shadow">
            <h3 class="text-lg font-semibold mb-2">Product Statistics</h3>
            <?php
            $total_product   = 0;
            $male_product    = 0;
            $female_product  = 0;
            $kids_product    = 0;
            $unisex_product  = 0;

            // Get total products
            $count_sql = "SELECT COUNT(*) AS total FROM product";
            if ($count_result = $conn->query($count_sql)) {
                if ($count_row = $count_result->fetch_assoc()) {
                    $total_product = (int)$count_row['total'];
                }
                $count_result->free();
            }

            // Get products per category
            $category_sql = "SELECT category, COUNT(*) AS total FROM product GROUP BY category";
            if ($category_result = $conn->query($category_sql)) {
                while ($row = $category_result->fetch_assoc()) {
                    $category = strtolower(trim($row['category']));
                    $count    = (int)$row['total'];

                    if ($category === 'men' || $category === 'male') {
                        $male_product += $count;
                    } elseif ($category === 'women' || $category === 'female') {
                        $female_product += $count;
                    } elseif ($category === 'kids' || $category === 'kid') {
                        $kids_product += $count;
                    } elseif ($category === 'unisex') {
                        $unisex_product += $count;
                    }
                }
                $category_result->free();
            }
            ?>
            <p class="text-gray-700">Total Products: <span class="font-bold"><?php echo $total_product; ?></span></p>
               <div class="flex space-x-4 mt-2">
              <p class="text-gray-700">Male Products: <span class="font-bold"><?php echo $male_product; ?></span></p>
              <p class="text-gray-700">Female Products: <span class="font-bold"><?php echo $female_product; ?></span></p>
              <p class="text-gray-700">Kids Products: <span class="font-bold"><?php echo $kids_product; ?></span></p>
              <p class="text-gray-700">Unisex Products: <span class="font-bold"><?php echo $unisex_product; ?></span></p> 
              </div>  
            <p class="text-gray-700">Last Updated: <span class="font-bold"><?php echo date("Y-m-d H:i:s"); ?></span></p>
                                  
            </div>     
        </div>
      </div>
     </div>
    <!-- ends product view -->

    <!-- view stock -->
    <div id= "viewstock" class="admin-section items-center justify-center w-full min-h-[calc(100vh-5rem)] hidden">
      <div class="w-full max-w-3xl mx-auto p-4">
        <h2 class="text-2xl font-semibold mb-4">View Stock</h2>
        <!-- Content for viewing stock goes here -->
        <div class="bg-white p-6 rounded shadow space-y-4">
          <table class="w-full table-auto border-collapse border border-gray-300">
            <thead>
              <tr class="bg-gray-200">
                <th class="border border-gray-300 px-4 py-2">product id</th>
                <th class="border border-gray-300 px-4 py-2">Product Name</th>
                <th class="border border-gray-300 px-4 py-2">Size</th>
                <th class="border border-gray-300 px-4 py-2">Stock Quantity</th>
                <th class="border border-gray-300 px-4 py-2">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php
              require_once 'databaseconn.php';
              $sql = "SELECT p.product_name, s.size_name, ps.size_stock ,p.product_id, ps.product_size_id 
                      FROM product_sizes ps
                      JOIN product p ON ps.product_id = p.product_id
                      JOIN sizes s ON ps.size_id = s.size_id
                      ORDER BY p.product_name, s.size_name";
              if ($result = $conn->query($sql)) {
                  while ($row = $result->fetch_assoc()) {
                      echo "<tr>";
                      echo "<td class='border border-gray-300 px-4 py-2'>" . htmlspecialchars($row['product_id']) . "</td>";
                      echo "<td class='border border-gray-300 px-4 py-2'>" . htmlspecialchars($row['product_name']) . "</td>";
                      echo "<td class='border border-gray-300 px-4 py-2'>" . htmlspecialchars($row['size_name']) . "</td>";
                      echo "<td class='border border-gray-300 px-4 py-2'>" . htmlspecialchars($row['size_stock']) . "</td>";
                      echo"<form action=\"deletestock.php\" method=\"post\">";
                      echo "<td class='border border-gray-300 px-4 py-2'>";
                      echo "<button type='submit' onclick=\"return confirm('Are you sure you want to delete this stock entry?')\" class='bg-red-500 text-white px-3 py-1 rounded cursor-pointer'>Delete</button>";
                      echo "<input type='hidden' name='product_size_id' value='" . htmlspecialchars($row['product_size_id']) . "'>";
                      echo "</form>";
                      echo "</tr>";
                  }
                  $result->free(); 
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <!-- view stock ends-->

    <!-- edit stock -->
    <div id= "editstock" class="admin-section items-center justify-center w-full min-h-[calc(100vh-5rem)] hidden">
      <div class="w-full  max-w-3xl mx-auto p-4">
        <h2 class="text-2xl font-semibold mb-4">Edit Stock</h2>
        <!-- Content for editing stock goes here -->
        <div class="bg-white p-6 rounded shadow space-y-4">
          <table class="w-full table-auto border-collapse border border-gray-300">
            <thead>
              <tr class="bg-gray-200">
                <th class="border border-gray-300 px-4 py-2">Product ID</th>
                <th class="border border-gray-300 px-4 py-2">Product Name</th>
                <th class="border border-gray-300 px-4 py-2">Size</th>
                <th class="border border-gray-300 px-4 py-2">Stock Quantity</th>
                <th class="border border-gray-300 px-4 py-2">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php
              require_once 'databaseconn.php';
              $sql = "SELECT p.product_name, s.size_name, ps.size_stock ,p.product_id, ps.product_size_id 
                      FROM product_sizes ps
                      JOIN product p ON ps.product_id = p.product_id
                      JOIN sizes s ON ps.size_id = s.size_id
                      ORDER BY p.product_name, s.size_name";
              if ($result = $conn->query($sql)) {
                  while ($row = $result->fetch_assoc()) {
                      echo "<tr>";
                      echo "<td class='border border-gray-300 px-4 py-2'>" . htmlspecialchars($row['product_id']) . "</td>";
                      echo "<td class='border border-gray-300 px-4 py-2'>" . htmlspecialchars($row['product_name']) . "</td>";
                      echo "<td class='border border-gray-300 px-4 py-2'>" . htmlspecialchars($row['size_name']) . "</td>";
                      echo "<td class='border border-gray-300 px-4 py-2'>" . htmlspecialchars($row['size_stock']) . "</td>";
                      echo "<td class='border border-gray-300 px-4 py-2'>";
                      echo "<form action=\"updatestock.php\" method=\"post\" onsubmit=\"return confirm('Are you sure you want to update this stock?');\">";
                      echo "<input type='number' name='new_stock' value='" . htmlspecialchars($row['size_stock']) . "' min='0' class='w-20 p-1 border rounded mr-2'>";
                      echo "<input type='hidden' name='product_size_id' value='" . htmlspecialchars($row['product_size_id']) . "'>";
                      echo "<button type='submit' class='bg-blue-500 text-white px-3 py-1 rounded cursor-pointer'>Update</button>";
                      echo "</form>";
                      echo "</td>";
                      echo "</tr>";
                  }
                  $result->free(); 
              }
              ?>
            </tbody>    
          </table>
        </div>
      </div>
    </div>
    <!-- edit stock ends-->


    <!-- edit product -->
     <div id="productedit" class="admin-section items-center justify-center w-full min-h-[calc(100vh-5rem)] hidden">
      <div class="w-full max-w-3xl mx-auto p-4">
        <h2 class="text-2xl font-semibold mb-4">Edit Product</h2>
       <p>Enter product id to edit</p> 
        <form method="get" action="editproduct.php" class="bg-white p-6 rounded shadow space-y-4">
          <label class="block">
            <span class="text-sm font-medium">Product ID</span>
            <input type="number" name="product_id" required class="w-full mt-1 p-2 border rounded">
          </label>  
          <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Edit Product</button>
        </form>
      </div>
    </div>
    <!-- edit product ends-->

    <!-- view users -->
     <div id="users" class="admin-section items-center justify-center w-full min-h-[calc(100vh-5rem)] hidden">
      <div class="w-full max-w-3xl mx-auto p-4">
        <h2 class="text-2xl font-semibold mb-4">View Users</h2>
        <!-- Content for viewing users goes here -->
        <div class="bg-white p-6 rounded shadow space-y-4">
          <table class="w-full table-auto border-collapse border border-gray-300">
            <thead>
              <tr class="bg-gray-200">
                <th class="border border-gray-300 px-4 py-2">Profile</th>
                <th class="border border-gray-300 px-4 py-2">User ID</th>
                <th class="border border-gray-300 px-4 py-2">Full Name</th>
                <th class="border border-gray-300 px-4 py-2">Email</th>
                <th class="border border-gray-300 px-4 py-2">Created At</th>
              </tr>
            </thead>
            <tbody>
              <?php
              require_once 'databaseconn.php';
              $sql = "SELECT user_id, full_name, email, profile_picture, created_at, role FROM users ORDER BY created_at DESC";

              if (!isset($conn) || $conn->connect_error) {
                  $err = isset($conn) ? $conn->connect_error : 'Connection not initialized';
                  echo "<tr><td colspan='5' class='border border-gray-300 px-4 py-2 text-center text-red-500'>DB error: " . htmlspecialchars($err) . "</td></tr>";
              } else {
                  if ($result = $conn->query($sql)) {
                      while ($row = $result->fetch_assoc()) {
                          if ($row['role'] === 'customer') {
                              echo "<tr>";
                              echo "<td class='border border-gray-300 px-4 py-2'>";

                              $profilePath = !empty($row['profile_picture']) ? __DIR__ . '/uploads/profiles/' . $row['profile_picture'] : '';
                              if (!empty($profilePath) && file_exists($profilePath)) {
                                  $src = 'uploads/profiles/' . htmlspecialchars($row['profile_picture']);
                                  echo "<img src='" . $src . "' alt='Profile Picture' class='w-10 h-10 rounded-full object-cover'>";
                              } else {
                                  echo "<div class='w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center text-gray-600'>N/A</div>";
                              }

                              echo "</td>";
                              echo "<td class='border border-gray-300 px-4 py-2'>" . htmlspecialchars($row['user_id']) . "</td>";
                              echo "<td class='border border-gray-300 px-4 py-2'>" . htmlspecialchars($row['full_name']) . "</td>";
                              echo "<td class='border border-gray-300 px-4 py-2'>" . htmlspecialchars($row['email']) . "</td>";
                              echo "<td class='border border-gray-300 px-4 py-2'>" . htmlspecialchars($row['created_at']) . "</td>";
                              echo "</tr>";
                          }
                      }
                      $result->free();
                  } else {
                      echo "<tr><td colspan='5' class='border border-gray-300 px-4 py-2 text-center text-red-500'>Error fetching users: " . htmlspecialchars($conn->error) . "</td></tr>";
                  }
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <!-- view users ends-->

    <!-- view orders -->
    <div id="orders" class="admin-section items-center justify-center w-full min-h-[calc(100vh-5rem)] hidden">
      <div class="w-full max-w-4xl mx-auto p-4">
        <h2 class="text-2xl font-semibold mb-4">View Orders</h2>
        <div class="bg-white p-6 rounded shadow space-y-4 overflow-x-auto">
          <table class="w-full table-auto border-collapse border border-gray-300 text-sm">
            <thead>
              <tr class="bg-gray-200">
                <th class="border border-gray-300 px-3 py-2">Order ID</th>
                <th class="border border-gray-300 px-3 py-2">Customer</th>
                <th class="border border-gray-300 px-3 py-2">Total (NRs)</th>
                <th class="border border-gray-300 px-3 py-2">Payment Method</th>
                <th class="border border-gray-300 px-3 py-2">Payment Status</th>
                <th class="border border-gray-300 px-3 py-2">Order Status</th>
                <th class="border border-gray-300 px-3 py-2">Created At</th>
              </tr>
            </thead>
            <tbody>
              <?php
              require_once 'databaseconn.php';

              $orderSql = "SELECT o.order_id, o.total_amount, o.payment_method, o.payment_status, o.order_status, o.created_at, u.full_name
                           FROM orders o
                           JOIN users u ON o.user_id = u.user_id
                           ORDER BY o.created_at DESC";

              if (!isset($conn) || $conn->connect_error) {
                  $err = isset($conn) ? $conn->connect_error : 'Connection not initialized';
                  echo "<tr><td colspan='7' class='border border-gray-300 px-3 py-2 text-center text-red-500'>DB error: " . htmlspecialchars($err) . "</td></tr>";
              } else {
                  if ($result = $conn->query($orderSql)) {
                      if ($result->num_rows === 0) {
                          echo "<tr><td colspan='7' class='border border-gray-300 px-3 py-2 text-center text-gray-500'>No orders found.</td></tr>";
                      } else {
                          while ($row = $result->fetch_assoc()) {
                              echo "<tr>";
                              echo "<td class='border border-gray-300 px-3 py-2'>" . htmlspecialchars($row['order_id']) . "</td>";
                              echo "<td class='border border-gray-300 px-3 py-2'>" . htmlspecialchars($row['full_name']) . "</td>";
                              echo "<td class='border border-gray-300 px-3 py-2'>" . number_format((float)$row['total_amount'], 2) . "</td>";
                              echo "<td class='border border-gray-300 px-3 py-2'>" . htmlspecialchars($row['payment_method']) . "</td>";
                              echo "<td class='border border-gray-300 px-3 py-2'>" . htmlspecialchars($row['payment_status']) . "</td>";
                              echo "<td class='border border-gray-300 px-3 py-2'>" . htmlspecialchars($row['order_status']) . "</td>";
                              echo "<td class='border border-gray-300 px-3 py-2'>" . htmlspecialchars($row['created_at']) . "</td>";
                              echo "</tr>";
                          }
                          $result->free();
                      }
                  } else {
                      echo "<tr><td colspan='7' class='border border-gray-300 px-3 py-2 text-center text-red-500'>Error fetching orders: " . htmlspecialchars($conn->error) . "</td></tr>";
                  }
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <!-- view orders ends-->

   


  </main>
  </div>


<script>
  // Show section function
  function showSection(id) {
    const el = document.getElementById(id);
    // If the target section doesn't exist, do nothing so the current view stays
    if (!el) {
      alert('Section "' + id + '" not found');
      return;
    }

    document.querySelectorAll('.admin-section').forEach(s => s.classList.add('hidden'));
    el.classList.remove('hidden');
  }
  </script>


   
</body>
</html>