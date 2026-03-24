<?php include 'header.php';?>
<?php
    include 'crudop/databaseconn.php';
    include 'crudop/function.php';  
   if (!isset($_GET['id'])) {
    echo "<p class='text-center text-red-500'>Invalid product.</p>";
    include 'footer.php';
    exit;
}

$product_id = (int)$_GET['id'];
?>

<main class="container mx-auto px-4 py-10">
    <?php showProductDetail($conn, $product_id); ?>
</main>

<!-- average rating -->
 <section class="container mx-auto px-4 py-10">
    <?php
$avg = $conn->query("
    SELECT AVG(rating) as avg_rating, COUNT(*) as total_reviews
    FROM product_reviews
    WHERE product_id = $product_id
")->fetch_assoc();

$avg_rating = round($avg['avg_rating'], 1);
$total_reviews = $avg['total_reviews'];
    ?>
    <h2 class="text-2xl font-bold mb-4 text-slate-900">Average Rating</h2>
    <div class="flex items-center gap-4">
        <div class="flex items-center">
            <?php for ($i = 0; $i < 5; $i++): ?>
                <?php if ($i < floor($avg_rating)): ?>
                    <span class="text-amber-500">⭐</span>
                <?php elseif ($i < $avg_rating): ?>
                    <span class="text-amber-500">⭐</span> <!-- Consider using a half-star icon for better accuracy -->
                <?php else: ?>
                    <span class="text-slate-300">⭐</span>
                <?php endif; ?>
            <?php endfor; ?>        
        </div>
        <span class="text-slate-700 font-semibold text-lg"><?= $avg_rating ?>/5</span>
        <span class="text-slate-500">(<?= $total_reviews ?> reviews)</span>
    </div>      

                                    </section>

<!-- to display reviews -->
 <section class="container mx-auto px-4 py-10">
    <h2 class="text-2xl font-bold mb-6 text-slate-900">Customer Reviews</h2>
    <?php
    $sql = "SELECT pr.rating, pr.review, u.full_name, u.profile_picture, pr.created_at
            FROM product_reviews pr
            JOIN users u ON pr.user_id = u.user_id
            WHERE pr.product_id = ?
            ORDER BY pr.created_at DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0): ?>
        <p class="text-slate-500">No reviews yet. Be the first to review this product!</p>
    <?php else: ?>
        <div class="space-y-6">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="bg-white p-6 rounded-xl shadow flex gap-4">
                    <img src="crudop/images/<?= htmlspecialchars($row['profile_picture']) ?>" alt="<?= htmlspecialchars($row['full_name']) ?>'s profile picture" class="w-12 h-12 object-cover rounded-full border border-slate-200">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <h3 class="font-semibold text-slate-900"><?= htmlspecialchars($row['full_name']) ?></h3>
                            <div class="flex items-center">
                                <?php for ($i = 0; $i < 5; $i++): ?>
                                    <?php if ($i < $row['rating']): ?>
                                        <span class="text-amber-500">⭐</span>
                                    <?php else: ?>
                                        <span class="text-slate-300">⭐</span>
                                    <?php endif; ?>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <p class="text-slate-700 mb-2"><?= nl2br(htmlspecialchars($row['review'])) ?></p>
                        <p class="text-xs text-slate-400"><?= date('F j, Y', strtotime($row['created_at'])) ?></p>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>     
</section>



<!-- similar products section -->
<section class="container mx-auto px-4 py-10">
    <h2 class="text-2xl font-bold mb-6 text-slate-900">Similar Products</h2>
    <div class="relative">
                <!-- Left/Right buttons -->
                <button aria-label="Scroll left" onclick="document.getElementById('featured-scroll').scrollBy({ left: -400, behavior: 'smooth' })" class="hidden md:flex items-center justify-center absolute left-0 top-1/2 -translate-y-1/2 -translate-x-12 z-20 h-10 w-10 rounded-full  shadow-md text-slate-700  transition-all">
                    ←
                </button>
                <button aria-label="Scroll right" onclick="document.getElementById('featured-scroll').scrollBy({ left: 400, behavior: 'smooth' })" class="hidden md:flex items-center justify-center absolute right-0 top-1/2 -translate-y-1/2 translate-x-12 z-20 h-10 w-10 rounded-full shadow-md text-slate-700   transition-all">
                    →
                </button>

                <!-- Scroll container (Horizontal) -->
                <div id="featured-scroll" class="overflow-x-auto overflow-y-hidden no-scrollbar snap-x snap-mandatory flex gap-4 px-2 py-4">
                    <?php if(isset($conn)) { showsimilarproducts($conn, $product_id); } else { echo '<p>Database connection error</p>'; } ?>
                    <!-- View All card -->
                    <a href="products.php" class="snap-start flex-shrink-0 w-50 md:w-80 flex items-center justify-center bg-amber-50 rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2">
                        <div class="p-6 text-center">
                            <p class="text-sm text-amber-600 font-medium">Explore More</p>
                            <h4 class="font-bold text-amber-700 text-xl mt-2">View All </h4>
                            <p class="text-sm text-slate-600 mt-2"> Similar Items</p>
                            <div class="mt-4">
                                <button class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-lg transition-colors">View All</button>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
</section>
 <!-- Scrollbar styling (keep scrollbars visible) -->
            <style>
                .no-scrollbar {
                    -webkit-overflow-scrolling: touch; /* Smooth scrolling on iOS */
                }
            </style>


<!-- to insert review -->
<section class="container mx-auto mt-5 px-4 py-10">
<?php if (isset($_SESSION['user_id'])): ?>
<div class="mt-10 bg-white p-6 rounded-xl shadow">

    <h3 class="text-xl font-bold mb-4">Write a Review</h3>

    <form action="add_review.php" method="POST" class="space-y-4">

        <!-- Use the product ID from the URL so it's always available -->
        <input type="hidden" name="product_id" value="<?= $product_id ?>">

        <!-- Rating form -->
        <div>
            <label class="block mb-1 font-medium">Rating</label>
            <select name="rating" required class="border rounded px-3 py-2 w-full">
                <option value="">Select rating</option>
                <option value="5">⭐⭐⭐⭐⭐ - Excellent</option>
                <option value="4">⭐⭐⭐⭐ - Good</option>
                <option value="3">⭐⭐⭐ - Average</option>
                <option value="2">⭐⭐ - Poor</option>
                <option value="1">⭐ - Very Bad</option>
            </select>
        </div>

        <!-- Review -->
        <div>
            <label class="block mb-1 font-medium">Review</label>
            <textarea name="review" rows="4" class="border rounded px-3 py-2 w-full"
                placeholder="Write your review..."></textarea>
        </div>

        <button type="submit" class="flex-1 border-2 border-black py-4 rounded-xl text-lg font-semibold hover:bg-amber-100 hover:text-white transition text-center">
            Submit Review
        </button>
    </form>

</div>
<?php else: ?>
<p class="mt-6 text-red-500">
    Please <a href="login.php" class="underline">login</a> to write a review.
</p>
<?php endif; ?>
</section>

<?php include 'footer.php';?>