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

function getPendingOrderFromSession(): ?array
{
    if (!isset($_SESSION['pending_payment']) || !is_array($_SESSION['pending_payment'])) {
        return null;
    }

    $pending = $_SESSION['pending_payment'];
    if (!isset($pending['order_id'], $pending['gateway'], $pending['amount'])) {
        return null;
    }

    return $pending;
}

function markOrderStatus(mysqli $conn, int $orderId, string $paymentStatus, string $orderStatus): bool
{
    $stmt = $conn->prepare('UPDATE orders SET payment_status = ?, order_status = ? WHERE order_id = ?');
    if (!$stmt) {
        return false;
    }

    $stmt->bind_param('ssi', $paymentStatus, $orderStatus, $orderId);
    $ok = $stmt->execute();
    $stmt->close();

    return $ok;
}

function clearCartAfterSuccess(): void
{
    unset($_SESSION['cart']);
    $_SESSION['cart_count'] = 0;
}

$gateway = isset($_GET['gateway']) ? strtolower(trim($_GET['gateway'])) : '';
$status = isset($_GET['status']) ? strtolower(trim($_GET['status'])) : '';

$pending = getPendingOrderFromSession();
if (!$pending || ($gateway !== 'khalti' && $gateway !== 'esewa')) {
    $_SESSION['order_error'] = 'Payment session not found. Please try checkout again.';
    header('Location: payment.php');
    exit;
}

$orderId = (int) $pending['order_id'];
$amount = (float) $pending['amount'];
$expectedGateway = strtolower($pending['gateway']);

if ($expectedGateway !== $gateway) {
    $_SESSION['order_error'] = 'Gateway mismatch detected. Please retry payment.';
    header('Location: payment.php');
    exit;
}

$paid = false;
$error = '';

if ($gateway === 'khalti') {
    $pidx = isset($_GET['pidx']) ? trim($_GET['pidx']) : '';

    if ($pidx === '') {
        $error = 'Khalti payment reference missing.';
    } else {
        $payload = json_encode(['pidx' => $pidx]);

        $ch = curl_init(KHALTI_LOOKUP_URL);
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
            $error = 'Khalti lookup failed: ' . $curlErr;
        } else {
            $lookup = json_decode($response, true);
            $khaltiStatus = isset($lookup['status']) ? strtolower((string) $lookup['status']) : '';
            $khaltiAmountPaisa = isset($lookup['total_amount']) ? (int) $lookup['total_amount'] : 0;

            if ($khaltiStatus === 'completed' && $khaltiAmountPaisa === (int) round($amount * 100)) {
                $paid = true;
            } else {
                $error = 'Khalti payment was not completed or amount mismatched.';
            }
        }
    }
}

if ($gateway === 'esewa') {
    // eSewa sends status in callback query; in sandbox, status=COMPLETE for successful payment.
    if ($status === 'complete' || $status === 'success') {
        $paid = true;
    } else {
        $error = 'eSewa payment not completed.';
    }
}

if ($paid) {
    if (markOrderStatus($conn, $orderId, 'paid', 'pending')) {
        clearCartAfterSuccess();
        unset($_SESSION['pending_payment']);

        $_SESSION['order_success'] = 'Payment successful. Your order has been placed.';
        header('Location: index.php');
        exit;
    }

    $error = 'Payment captured but failed to update order status.';
}

markOrderStatus($conn, $orderId, 'failed', 'cancelled');
unset($_SESSION['pending_payment']);

$_SESSION['order_error'] = $error !== '' ? $error : 'Payment failed. Please try again.';
header('Location: payment.php?total=' . urlencode((string)$amount));
exit;
