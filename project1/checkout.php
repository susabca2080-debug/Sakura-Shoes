<?php
session_start();

// Require login before checkout
if (!isset($_SESSION['user_id'])) {
    header('Location: log_reg/login.php');
    exit;
}

// DB connection
require_once 'crudop/databaseconn.php';

// Fetch latest saved address for this user from user_addresses table
$user_id = (int) $_SESSION['user_id'];
$address = null;

$stmt = $conn->prepare("SELECT full_name, phone, province, city, area, street_address, additional_address
            FROM user_addresses
            WHERE user_id = ?
            ORDER BY created_at DESC
            LIMIT 1");
if ($stmt) {
  $stmt->bind_param('i', $user_id);
  if ($stmt->execute()) {
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
      $address = $result->fetch_assoc();
    }
  }
  $stmt->close();
}

// Normalize cart
$cart = isset($_SESSION['cart']) && is_array($_SESSION['cart']) ? $_SESSION['cart'] : [];

// Filter by selected keys if provided
$selected = isset($_POST['selected_keys']) && is_array($_POST['selected_keys']) ? $_POST['selected_keys'] : [];

if ($selected) {
    $items = [];
    foreach ($selected as $key) {
        if (isset($cart[$key])) {
            $items[$key] = $cart[$key];
        }
    }
} else {
    $items = $cart;
}

include 'header.php';

$total = 0;
foreach ($items as $it) {
    $total += $it['price'] * $it['quantity'];
}
?>

<div class="max-w-5xl mx-auto py-10 px-4">
  <h2 class="text-3xl font-extrabold mb-6 text-slate-900">Checkout</h2>

  <?php if (empty($items)): ?>
    <p class="text-slate-500 mb-4">No items selected for checkout.</p>
    <a href="cart.php" class="inline-block bg-slate-900 text-white px-5 py-2 rounded-lg text-sm font-semibold hover:bg-amber-600 transition-colors">Back to Cart</a>
  <?php else: ?>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        
        <?php if (empty($address)){ ?>
        <div class="flex-1 justify-between items-center mb-4">
         <h3 class="text-lg font-semibold text-slate-900">Shipping Information</h3>
          <p class="text-slate-500 mb-4">No address saved yet. Please add one from the Edit link above.</p>
          <a href="edit_shipping.php" class="inline-block bg-slate-900 text-white px-5 py-2 rounded-lg text-sm font-semibold hover:bg-amber-600 transition-colors">Add Address</a>
          </div>
        <?php } else { ?>
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-lg font-semibold text-slate-900">Shipping Information</h3>
          <a href="edit_shipping.php" class="text-sm text-amber-600 hover:underline">Edit</a>
        </div>
        <div class="text-sm text-slate-700 mb-6">
         
            <p class="font-semibold text-slate-900">
              <?= htmlspecialchars($address['full_name']) ?>
              <span class="text-xs text-slate-500 ml-2">(<?= htmlspecialchars($address['phone']) ?>)</span>
            </p>
            <p><?= htmlspecialchars($address['street_address']) ?></p>
            
              <p><?= htmlspecialchars($address['area']) ?></p>
           
            <p><?= htmlspecialchars($address['city']) ?><?= $address['province'] ? ', ' . htmlspecialchars($address['province']) : '' ?></p>
           
              <p class="text-slate-500 text-xs">Note: <?= htmlspecialchars($address['additional_address']) ?></p>
            </div>
          <?php } ?>
        
          
        <h3 class="text-lg font-semibold mb-4 text-slate-900">Items</h3>
        <ul class="space-y-4 text-sm">
          <?php foreach ($items as $item): ?>
            <li class="flex justify-between items-center border-b border-slate-100 pb-3">
              <div class="flex items-center gap-3">
                <img src="crudop/images/<?= $item['image'] ?>" class="w-12 h-12 object-cover rounded border border-slate-200" alt="<?= htmlspecialchars($item['product_name']) ?>">
                <div>
                  <p class="font-semibold text-slate-900"><?= $item['product_name'] ?></p>
                  <p class="text-xs text-slate-500">Size <?= $item['size'] ?> · Qty <?= $item['quantity'] ?></p>
                </div>
              </div>
              <span class="font-semibold text-slate-900">NRS <?= number_format($item['price'] * $item['quantity']) ?></span>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>

      <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col justify-between">
        <div>
          <h3 class="text-lg font-semibold text-slate-900 mb-4">Order Summary</h3>
          <div class="flex justify-between text-sm text-slate-700 mb-2">
            <span>Subtotal</span>
            <span>NRS <?= number_format($total) ?></span>
          </div>
          <div class="flex justify-between text-sm text-slate-500 mb-4">
            <span>Shipping</span>
            <span>NRS 99</span>
          </div>
          <div class="border-t border-slate-200 pt-4 flex justify-between text-base font-bold text-slate-900">
            <span>Total</span>
            <span>NRS <?= number_format($total + 99) ?></span>
          </div>
        </div>
        

        <div class="mt-6 flex flex-col gap-3">
          <a <?= empty($address) ? 'href="edit_shipping.php"' : 'href="payment.php?total=' . ($total + 99) . '"' ?> class="w-full bg-slate-900 text-white py-3 rounded-lg text-sm font-semibold cursor-pointer hover:bg-amber-600 transition-colors flex items-center justify-center" >
            Proceed to Payment
          </a>
          <a href="cart.php" class="w-full border border-slate-300 text-slate-700 py-3 rounded-lg text-sm font-semibold hover:border-amber-500 hover:text-amber-600 transition-colors text-center">Back to Cart</a>
        </div>
      </div>
    </div>
  <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
