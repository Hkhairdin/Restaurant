<?php

include 'config.php';
session_start();

// require login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// pull role
$role = $_SESSION['role'] ?? 'user';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <h1>Khairdin Restaurant</h1>
    <nav>
        <a href="index.php">Home</a> |
        <!-- only admins get Categories & Products -->
        <?php if ($role === 'admin'): ?>
            <a href="manage_categories.php">Categories</a> |
            <a href="manage_products.php">Products</a> |
        <?php endif; ?>
        <!-- everyone gets Order & Bills -->
        <a href="place_order.php">Order</a> |
        <a href="view_bills.php">Bills</a> |
        <a href="logout.php">Logout</a>
    </nav>
</header>

<main>
    <h2 style="text-align:center;">Dashboard (<?php echo htmlspecialchars($role); ?>)</h2>

    <div class="gallery">
        <img src="assets/food1.jpeg" alt="Dish 1">
        <img src="assets/food2.jpeg" alt="Dish 2">
        <img src="assets/food3.jpeg" alt="Dish 3">
        <img src="assets/food4.jpeg" alt="Dish 4">
    </div>

    <div style="text-align: center; margin-top: 20px;">
        <!-- everyone -->
        <a href="place_order.php"><button class="nav-btn">Place Order</button></a>

        <!-- admin only -->
        <?php if ($role === 'admin'): ?>
            <a href="manage_categories.php"><button class="nav-btn">Manage Categories</button></a>
            <a href="manage_products.php"><button class="nav-btn">Manage Products</button></a>
        <?php endif; ?>

        <!-- everyone -->
        <a href="view_bills.php"><button class="nav-btn">View Bills</button></a>
    </div>
</main>

<footer>
    <p>&copy; <?php echo date('Y'); ?> Khairdin Restaurant. All rights reserved.</p>
</footer>
</body>
</html>
