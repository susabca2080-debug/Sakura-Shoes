<?php
session_start();

// Require login
if (!isset($_SESSION['user_id'])) {
		header('Location: log_reg/login.php');
		exit;
}

require_once 'crudop/databaseconn.php';

$user_id = (int) $_SESSION['user_id'];
$errors  = [];
$success = false;

// Handle form submit: insert new address row (no address in session)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$full_name        = trim($_POST['full_name'] ?? '');
		$phone            = trim($_POST['phone'] ?? '');
		$province         = trim($_POST['province'] ?? '');
		$city             = trim($_POST['city'] ?? '');
		$area             = trim($_POST['area'] ?? '');
		$street_address   = trim($_POST['street_address'] ?? '');
		$additional_addr  = trim($_POST['additional_address'] ?? '');

		if ($full_name === '') {
				$errors[] = 'Full name is required.';
		}
		if ($phone === '') {
				$errors[] = 'Phone number is required.';
		}
		if ($street_address === '') {
				$errors[] = 'Street address is required.';
		}

		if (empty($errors)) {
				$stmt = $conn->prepare("INSERT INTO user_addresses
						(user_id, full_name, phone, province, city, area, street_address, additional_address)
						VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
				if ($stmt) {
						$stmt->bind_param(
								'isssssss',
								$user_id,
								$full_name,
								$phone,
								$province,
								$city,
								$area,
								$street_address,
								$additional_addr
						);
						if ($stmt->execute()) {
								$success = true;
								// After saving, go back to checkout
								header('Location: checkout.php');
								exit;
						} else {
								$errors[] = 'Failed to save address. Please try again.';
						}
						$stmt->close();
				} else {
						$errors[] = 'Database error. Please try again later.';
				}
		}
}

// Load latest existing address for this user to prefill form (optional)
$address = [
		'full_name'         => '',
		'phone'             => '',
		'province'          => '',
		'city'              => '',
		'area'              => '',
		'street_address'    => '',
		'additional_address'=> '',
];

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

include 'header.php';
?>

<div class="max-w-3xl mx-auto py-10 px-4">
	<h2 class="text-3xl font-extrabold mb-6 text-slate-900">Edit Shipping Address</h2>

	<?php if (!empty($errors)): ?>
		<div class="mb-4 bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg p-4">
			<ul class="list-disc list-inside">
				<?php foreach ($errors as $error): ?>
					<li><?= htmlspecialchars($error) ?></li>
				<?php endforeach; ?>
			</ul>
		</div>
	<?php endif; ?>

	<form method="post" class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 space-y-4">
		<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
			<div>
				<label class="block text-sm font-medium text-slate-700 mb-1">Full Name<span class="text-red-500">*</span></label>
				<input type="text" name="full_name" value="<?= htmlspecialchars($address['full_name']) ?>" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
			</div>
			<div>
				<label class="block text-sm font-medium text-slate-700 mb-1">Phone<span class="text-red-500">*</span></label>
				<input type="text" name="phone" value="<?= htmlspecialchars($address['phone']) ?>" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
			</div>
		</div>

		<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
			<div>
				<label class="block text-sm font-medium text-slate-700 mb-1">Province</label>
				<input type="text" name="province" value="<?= htmlspecialchars($address['province']) ?>" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
			</div>
			<div>
				<label class="block text-sm font-medium text-slate-700 mb-1">City</label>
				<input type="text" name="city" value="<?= htmlspecialchars($address['city']) ?>" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
			</div>
		</div>

		<div>
			<label class="block text-sm font-medium text-slate-700 mb-1">Area</label>
			<input type="text" name="area" value="<?= htmlspecialchars($address['area']) ?>" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
		</div>

		<div>
			<label class="block text-sm font-medium text-slate-700 mb-1">Street Address<span class="text-red-500">*</span></label>
			<textarea name="street_address" rows="3" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500"><?= htmlspecialchars($address['street_address']) ?></textarea>
		</div>

		<div>
			<label class="block text-sm font-medium text-slate-700 mb-1">Additional Address Info (optional)</label>
			<input type="text" name="additional_address" value="<?= htmlspecialchars($address['additional_address']) ?>" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
		</div>

		<div class="mt-6 flex items-center justify-between gap-3">
			<a href="checkout.php" class="inline-flex justify-center items-center border border-slate-300 text-slate-700 px-4 py-2 rounded-lg text-sm font-semibold hover:border-amber-500 hover:text-amber-600 transition-colors">Cancel</a>
			<button type="submit" class="inline-flex justify-center items-center bg-slate-900 text-white px-5 py-2 rounded-lg text-sm font-semibold hover:bg-amber-600 transition-colors">Save Address</button>
		</div>
	</form>
</div>

<?php include 'footer.php'; ?>

