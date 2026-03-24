<?php
session_start();

// Handle cart actions: increase, decrease, remove
// Style: still using a simple switch('inc'|'dec'|'remove') like your original,
// but we encode the item key together with the action in one field
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cart_action'])) {
    $parts  = explode(':', $_POST['cart_action'], 2);
    $action = $parts[0] ?? '';
    $key    = $parts[1] ?? '';

    if ($key !== '' && isset($_SESSION['cart'][$key])) {
        switch ($action) {
            case 'inc':
                $_SESSION['cart'][$key]['quantity'] += 1;
                break;
            case 'dec':
                $_SESSION['cart'][$key]['quantity'] -= 1;
                if ($_SESSION['cart'][$key]['quantity'] <= 0) {
                    unset($_SESSION['cart'][$key]);
                }
                break;
            case 'remove':
                unset($_SESSION['cart'][$key]);
                break;
        }
    }

    // Avoid resubmission on refresh
    header('Location: cart.php');
    exit;
}

include 'header.php';

// Normalize cart to a safe array
$items = isset($_SESSION['cart']) && is_array($_SESSION['cart']) ? $_SESSION['cart'] : [];

$_SESSION['cart_count'] = count($items);
?>

<div class="max-w-5xl mx-auto py-10 px-4">
<a href="index.php"><img src="crudop/images/arrow.png" alt="arrow Icon" class="h-6 w-6"></a>
    <h2 class="text-3xl font-extrabold mb-4 mt-6 text-slate-900 flex items-center justify-between">
        <span>My Cart</span>
        <span class="text-sm font-medium text-slate-500">( <?= count($items) ?> items)</span>
    </h2>

    <?php if (count($items) === 0): ?>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8 text-center">
            <p class="text-slate-500 mb-4">Your cart is empty.</p>
            <a href="index.php" class="inline-block bg-slate-900 text-white px-5 py-2 rounded-lg text-sm font-semibold hover:bg-amber-600 transition-colors">Continue shopping</a>
        </div>
    <?php else: ?>

        <form method="post" action="checkout.php" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Items list -->
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100 text-slate-700">
                        <tr>
                            <th class="p-3 text-center">
                                <input type="checkbox" id="select-all" class="w-4 h-4">
                            </th>
                            <th class="p-3 text-left">Product</th>
                            <th class="p-3 text-center">Size</th>
                            <th class="p-3 text-center">Qty</th>
                            <th class="p-3 text-right">Price</th>
                            <th class="p-3 text-center">Remove</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $total = 0; ?>
                    <?php foreach ($items as $key => $item): ?>
                        <tr class="border-t border-slate-100">
                            <td class="p-3 text-center align-middle">
                                <input type="checkbox" name="selected_keys[]" value="<?= htmlspecialchars($key) ?>" class="w-4 h-4">
                            </td>
                            <td class="p-3 ">
                                <div class="flex gap-3 items-center">
                                    <a href="productdetail.php?id=<?= $item['product_id'] ?>"><img src="crudop/images/<?= $item['image'] ?>" class="w-10 h-10 object-cover rounded-lg border border-slate-200" alt="<?= htmlspecialchars($item['product_name']) ?>">
                                      </a>
                                    <div>
                                        <p class="font-semibold text-slate-900 text-sm line-clamp-2"><?= $item['product_name'] ?></p>
                                        <p class="text-xs text-slate-500 mt-1">NRS <?= number_format($item['price']) ?> each</p>
                                    </div>
                                </div>
                            </td>
                            <td class="p-3 text-center align-middle text-slate-700"><?= $item['size'] ?></td>
                            <td class="p-3 text-center align-middle text-slate-700">
                                <div class="inline-flex items-center justify-center gap-2">
                                    <button
                                        type="submit"
                                        name="cart_action"
                                        value="dec:<?= htmlspecialchars($key) ?>"
                                        formaction="cart.php"
                                        class="w-7 h-7 flex items-center justify-center border border-slate-300 rounded-full text-xs hover:border-amber-500 hover:text-amber-600">-</button>
                                    <span class="min-w-[1.5rem] text-center text-sm font-semibold"><?= $item['quantity'] ?></span>
                                    <button
                                        type="submit"
                                        name="cart_action"
                                        value="inc:<?= htmlspecialchars($key) ?>"
                                        formaction="cart.php"
                                        class="w-7 h-7 flex items-center justify-center border border-slate-300 rounded-full text-xs hover:border-amber-500 hover:text-amber-600">+</button>
                                </div>
                            </td>
                            <td class="p-3 text-right align-middle font-semibold text-slate-900">NRS <?= number_format($item['price'] * $item['quantity']) ?></td>
                            <td class="p-3 text-center align-middle">
                                <button
                                    type="submit"
                                    name="cart_action"
                                    value="remove:<?= htmlspecialchars($key) ?>"
                                    formaction="cart.php"
                                    class="text-xs text-red-500 hover:text-red-600">Remove</button>
                            </td>
                        </tr>
                        <?php $total += $item['price'] * $item['quantity']; ?>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Summary card -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">Order Summary</h3>
                    <div class="flex justify-between text-sm text-slate-700 mb-2">
                        <span>Subtotal</span>
                        <span>NRS <?= number_format($total) ?></span>
                    </div>
                    <div class="flex justify-between text-sm text-slate-500 mb-4">
                        <span>Shipping</span>
                        <span>Calculated at checkout</span>
                    </div>
                    <div class="border-t border-slate-200 pt-4 flex justify-between text-base font-bold text-slate-900">
                        <span>Total</span>
                        <span>NRS <?= number_format($total) ?></span>
                    </div>
                </div>

                <div class="mt-6 flex flex-col gap-3">
                    <button type="submit" class="w-full bg-slate-900 text-white py-3 rounded-lg text-sm font-semibold hover:bg-amber-600 transition-colors text-center">
                        Proceed to Checkout (Selected)
                    </button>
                    <a href="products.php" class="w-full border border-slate-300 text-slate-700 py-3 rounded-lg text-sm font-semibold hover:border-amber-500 hover:text-amber-600 transition-colors text-center">Continue Shopping</a>
                </div>
            </div>
        </form>

    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>

<script>
// Select/Deselect all checkboxes
document.addEventListener('DOMContentLoaded', function () {
    const selectAll = document.getElementById('select-all');
    if (!selectAll) return;
    selectAll.addEventListener('change', function () {
        document.querySelectorAll('input[name="selected_keys[]"]').forEach(cb => {
            cb.checked = selectAll.checked;
        });
    });
});
</script>