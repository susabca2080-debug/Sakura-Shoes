<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'crudop/databaseconn.php';
require_once 'payment_config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: log_reg/login.php');
    exit;
}

function fetchLatestAddressId(mysqli $conn, int $userId): ?int
{
    $stmt = $conn->prepare('SELECT address_id FROM user_addresses WHERE user_id = ? ORDER BY created_at DESC LIMIT 1');
    if (!$stmt) {
        return null;
    }

    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result ? $result->fetch_assoc() : null;
    $stmt->close();

    return $row ? (int) $row['address_id'] : null;
}

function createOrder(mysqli $conn, int $userId, int $addressId, float $total, string $paymentMethod, string $paymentStatus, string $orderStatus): ?int
{
    $stmt = $conn->prepare('INSERT INTO orders (user_id, address_id, total_amount, payment_method, payment_status, order_status) VALUES (?, ?, ?, ?, ?, ?)');
    if (!$stmt) {
        return null;
    }

    $stmt->bind_param('iidsss', $userId, $addressId, $total, $paymentMethod, $paymentStatus, $orderStatus);
    $ok = $stmt->execute();
    $newOrderId = $ok ? (int) $stmt->insert_id : null;
    $stmt->close();

    return $newOrderId;
}

function clearCart(): void
{
    unset($_SESSION['cart']);
    $_SESSION['cart_count'] = 0;
}

$error = '';
$success = '';
$postedTotal = 0;
$total = 0;

if (isset($_SESSION['order_error'])) {
    $error = (string) $_SESSION['order_error'];
    unset($_SESSION['order_error']);
}

if (isset($_GET['total']) && is_numeric($_GET['total'])) {
    $total = (float) $_GET['total'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['payment_method'])) {
    $method = strtolower(trim((string) $_POST['payment_method']));
    $postedTotal = isset($_POST['total']) && is_numeric($_POST['total']) ? (float) $_POST['total'] : 0;
    $total = $postedTotal > 0 ? $postedTotal : $total;

    if ($postedTotal <= 0) {
        $error = 'Invalid order total.';
    } else {
        $userId = (int) $_SESSION['user_id'];
        $addressId = fetchLatestAddressId($conn, $userId);

        if (!$addressId) {
            $error = 'No shipping address found. Please add one before payment.';
        } else {
            if ($method === 'cod') {
                $orderId = createOrder($conn, $userId, $addressId, $postedTotal, 'Cash on Delivery', 'unpaid', 'pending');
                if ($orderId) {
                    clearCart();
                    $_SESSION['order_success'] = 'Your Cash on Delivery order has been placed.';
                    header('Location: index.php');
                    exit;
                }

                $error = 'Failed to place Cash on Delivery order. Please try again.';
            }

            if ($method === 'khalti') {
                $orderId = createOrder($conn, $userId, $addressId, $postedTotal, 'Khalti', 'pending', 'pending');
                if (!$orderId) {
                    $error = 'Could not create order for Khalti payment.';
                } else {
                    $purchaseOrderId = 'ORDER-' . $orderId . '-' . time();
                    $returnUrl = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/payment_callback.php?gateway=khalti';
                    $websiteUrl = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/';

                    $_SESSION['pending_payment'] = [
                        'order_id' => $orderId,
                        'gateway' => 'khalti',
                        'amount' => $postedTotal
                    ];

                    $payload = json_encode([
                        'return_url' => $returnUrl,
                        'website_url' => $websiteUrl,
                        'amount' => (int) round($postedTotal * 100),
                        'purchase_order_id' => $purchaseOrderId,
                        'purchase_order_name' => 'Sakura Shoes Order #' . $orderId,
                        'customer_info' => [
                            'name' => isset($_SESSION['full_name']) ? (string) $_SESSION['full_name'] : 'Sakura Customer'
                        ]
                    ]);

                    $ch = curl_init(KHALTI_INITIATE_URL);
                    curl_setopt_array($ch, [
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_POST => true,
                        CURLOPT_POSTFIELDS => $payload,
                        CURLOPT_HTTPHEADER => [
                            'Authorization: Key ' . KHALTI_SECRET_KEY,
                            'Content-Type: application/json'
                        ]
                    ]);

                    $response = curl_exec($ch);
                    $curlErr = curl_error($ch);
                    curl_close($ch);

                    if ($response === false) {
                        $error = 'Khalti initiate failed: ' . $curlErr;
                    } else {
                        $result = json_decode($response, true);
                        if (isset($result['payment_url']) && is_string($result['payment_url'])) {
                            header('Location: ' . $result['payment_url']);
                            exit;
                        }

                        $msg = isset($result['detail']) ? (string) $result['detail'] : 'Unknown Khalti response.';
                        $error = 'Khalti initiation failed: ' . $msg;
                    }
                }
            }

            if ($method === 'esewa') {
                $orderId = createOrder($conn, $userId, $addressId, $postedTotal, 'eSewa', 'pending', 'pending');
                if (!$orderId) {
                    $error = 'Could not create order for eSewa payment.';
                } else {
                    $transactionUuid = 'ORDER-' . $orderId . '-' . time();
                    $successUrl = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/payment_callback.php?gateway=esewa&status=complete';
                    $failureUrl = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/payment_callback.php?gateway=esewa&status=failed';

                    $_SESSION['pending_payment'] = [
                        'order_id' => $orderId,
                        'gateway' => 'esewa',
                        'amount' => $postedTotal
                    ];

                    $signatureBase = 'total_amount=' . number_format($postedTotal, 2, '.', '') . ',transaction_uuid=' . $transactionUuid . ',product_code=' . ESEWA_MERCHANT_CODE;
                    $signature = base64_encode(hash_hmac('sha256', $signatureBase, ESEWA_SECRET, true));

                    echo '<form id="esewa-pay" action="' . htmlspecialchars(ESEWA_FORM_URL) . '" method="POST">';
                    echo '<input type="hidden" name="amount" value="' . htmlspecialchars(number_format($postedTotal, 2, '.', '')) . '">';
                    echo '<input type="hidden" name="tax_amount" value="0">';
                    echo '<input type="hidden" name="total_amount" value="' . htmlspecialchars(number_format($postedTotal, 2, '.', '')) . '">';
                    echo '<input type="hidden" name="transaction_uuid" value="' . htmlspecialchars($transactionUuid) . '">';
                    echo '<input type="hidden" name="product_code" value="' . htmlspecialchars(ESEWA_MERCHANT_CODE) . '">';
                    echo '<input type="hidden" name="product_service_charge" value="0">';
                    echo '<input type="hidden" name="product_delivery_charge" value="0">';
                    echo '<input type="hidden" name="success_url" value="' . htmlspecialchars($successUrl) . '">';
                    echo '<input type="hidden" name="failure_url" value="' . htmlspecialchars($failureUrl) . '">';
                    echo '<input type="hidden" name="signed_field_names" value="total_amount,transaction_uuid,product_code">';
                    echo '<input type="hidden" name="signature" value="' . htmlspecialchars($signature) . '">';
                    echo '</form>';
                    echo '<script>document.getElementById("esewa-pay").submit();</script>';
                    exit;
                }
            }
        }
    }
}

include 'header.php';
?>

<main class="bg-slate-50 py-8">
  <div class="container mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6 flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-slate-900 cursor-pointer">Payment</h1>
        <p class="mt-1 text-sm text-slate-500">Choose a payment method to complete your order.</p>
      </div>
      <a href="checkout.php" class="inline-flex items-center gap-2 bg-slate-900 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-amber-600 transition-colors">
        <span>Back to Checkout</span>
      </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Payment methods -->
      <section class="lg:col-span-2 space-y-0 lg:space-x-6 lg:space-y-0">
        <div class="grid grid-rows-3 md:grid-cols-3 gap-2 bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
          <button type="button" onclick="showsection('khalti')" class="w-full flex items-center justify-between px-4 py-3 rounded-xl border border-slate-200 hover:bg-amber-50 transition-colors text-left">
            <div>
              <p class="font-medium text-slate-900">Khalti</p>
              <p class="text-xs text-slate-500">Use your Khalti wallet for quick and secure checkout.</p>
            </div>
            <span class="text-xs font-semibold px-2 py-1 rounded-full bg-emerald-50 text-emerald-700">Preferred</span>
          </button>

          <button type="button" onclick="showsection('cod')" class="w-full flex items-center justify-between px-4 py-3 rounded-xl border border-slate-200 hover:bg-amber-50 transition-colors text-left">
            <div>
              <p class="font-medium text-slate-900">Cash on Delivery</p>
              <p class="text-xs text-slate-500">Pay in cash when your order is delivered.</p>
            </div>
            <span class="text-xs font-semibold px-2 py-1 rounded-full bg-slate-100 text-slate-700">Available</span>
          </button>

          <button type="button" onclick="showsection('bank')" class="w-full flex items-center justify-between px-4 py-3 rounded-xl border border-slate-200 hover:bg-amber-50 transition-colors text-left">
            <div>
              <p class="font-medium text-slate-900">Bank Transfer</p>
              <p class="text-xs text-slate-500">Transfer directly from your bank account.</p>
            </div>
            <span class="text-xs font-semibold px-2 py-1 rounded-full bg-slate-100 text-slate-700">Manual</span>
          </button>

          <button type="button" onclick="showsection('esewa')" class="w-full flex items-center justify-between px-4 py-3 rounded-xl border border-slate-200 hover:bg-amber-50 transition-colors text-left">
            <div>
              <p class="font-medium text-slate-900">eSewa</p>
              <p class="text-xs text-slate-500">Pay using your eSewa wallet for a seamless experience.</p>
            </div>
            <span class="text-xs font-semibold px-2 py-1 rounded-full bg-slate-100 text-slate-700">Preferred</span>
          </button>
        </div>

        <div class="mt-4 bg-white rounded-2xl shadow-sm border border-slate-100 p-6">

          <div id="khalti-detail" class="hidden payment-detail">
            <h3 class="font-semibold text-slate-900 mb-3">Khalti Payment Process</h3>
            <ol class="list-decimal list-inside space-y-2 text-sm text-slate-600 mb-4">
              <li>Click "Pay Now" to proceed</li>
              <li>You will be redirected to Khalti gateway</li>
              <li>Login to your Khalti account</li>
              <li>Confirm the transaction amount</li>
              <li>Complete payment securely</li>
              <li>Return to our site for order confirmation</li>
            </ol>
            <form method="post" action="payment.php">
              <input type="hidden" name="payment_method" value="khalti">
              <input type="hidden" name="total" value="<?php echo htmlspecialchars((string)$total); ?>">
              <button type="submit" class="mt-6 w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-2.5 rounded-lg transition-colors">
                Pay with Khalti (Sandbox)
              </button>
            </form>
          </div>

          <div id="cod-detail" class="payment-detail hidden">
            <h3 class="font-semibold text-slate-900 mb-3">Cash on Delivery Process</h3>
            <ol class="list-decimal list-inside space-y-2 text-sm text-slate-600 mb-4">
              <li>Confirm your order</li>
              <li>Our delivery partner will contact you</li>
              <li>Product will be delivered to your address</li>
              <li>Inspect the items upon delivery</li>
              <li>Pay the exact amount in cash</li>
              <li>Receive your order receipt</li>
            </ol>
            <form method="post" action="payment.php">
              <input type="hidden" name="payment_method" value="cod">
              <input type="hidden" name="total" value="<?php echo htmlspecialchars((string)$total); ?>">
              <button type="submit" class="mt-6 w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-2.5 rounded-lg transition-colors">
                Confirm Cash on Delivery
              </button>
            </form>
          </div>

          <div id="bank-detail" class="payment-detail hidden">
            <h3 class="font-semibold text-slate-900 mb-3">Bank Transfer Process</h3>
            <ol class="list-decimal list-inside space-y-2 text-sm text-slate-600 mb-4">
              <li>Place your order</li>
              <li>Receive bank account details via email</li>
              <li>Log in to your bank account</li>
              <li>Initiate fund transfer to provided account</li>
              <li>Share transaction receipt with us</li>
              <li>Order processing begins after verification</li>
            </ol>
            <button type="button" class="mt-6 w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-2.5 rounded-lg transition-colors cursor-not-allowed opacity-80">
              Bank Transfer (Manual Confirmation)
            </button>
          </div>

          <div id="esewa-detail" class="payment-detail hidden">
            <h3 class="font-semibold text-slate-900 mb-3">eSewa Payment Process</h3>
            <ol class="list-decimal list-inside space-y-2 text-sm text-slate-600 mb-4">
              <li>Click "Pay Now" to proceed</li>
              <li>Open eSewa app or website</li>
              <li>Scan the QR code or enter eSewa ID</li>
              <li>Enter the payment amount</li>
              <li>Confirm your login credentials</li>
              <li>Complete the transaction securely</li>
            </ol>
            <form method="post" action="payment.php">
              <input type="hidden" name="payment_method" value="esewa">
              <input type="hidden" name="total" value="<?php echo htmlspecialchars((string)$total); ?>">
              <button type="submit" class="mt-6 w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-2.5 rounded-lg transition-colors">
                Pay with eSewa (<?php echo ESEWA_IS_SANDBOX ? 'Sandbox' : 'Live'; ?>)
              </button>
            </form>
            <p class="mt-2 text-xs text-slate-500">
              <?php echo ESEWA_IS_SANDBOX ? 'Sandbox only: use eSewa test credentials.' : 'Live mode: use your real eSewa credentials.'; ?>
            </p>
          </div>
        </div>
      </section>

      <!-- Order summary -->
      <aside class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 h-40 lg:h-auto">
        <h2 class="text-lg font-semibold mb-4 text-slate-900">Order Summary</h2>
        <?php if ($error !== ''): ?>
          <p class="text-red-600 text-sm mb-3"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <?php if ($success !== ''): ?>
          <p class="text-green-600 text-sm mb-3"><?php echo htmlspecialchars($success); ?></p>
        <?php endif; ?>
        <div class="space-y-3 text-sm">
          <div class="flex justify-between text-slate-600">
            <span>Subtotal</span>
            <span>NRs <?php echo number_format($total); ?></span>
          </div>
          
          <div class="flex justify-between font-semibold text-slate-900 border-t border-dashed border-slate-200 pt-3 mt-2">
            <span>Total Payable</span>
            <span>NRs <?php echo number_format($total); ?></span>
          </div>
        </div>

        
      </aside>
    </div>
  </div>
  <script>
    function showsection(method) {
      // hide all payment detail blocks
      var details = document.querySelectorAll('.payment-detail');
      details.forEach(function (el) {
        el.classList.add('hidden');
      });

      // show selected method details
      var active = document.getElementById(method + '-detail');
      if (active) {
        active.classList.remove('hidden');
      }
    }
  </script>
</main>

<?php include 'footer.php'; ?>