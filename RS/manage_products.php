<?php
include 'config.php';
session_start();
if ($_SESSION['role'] !== 'admin') exit('Access denied.');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $conn->prepare("INSERT INTO product (name, category_id, price) VALUES (?, ?, ?)");
    $stmt->bind_param("sid", $_POST['name'], $_POST['category'], $_POST['price']);
    $stmt->execute();
}
$categories = $conn->query("SELECT * FROM category");
$products = $conn->query("SELECT p.id, p.name, c.name as category, p.price FROM product p JOIN category c ON p.category_id = c.id");
?>
<!DOCTYPE html>
<html>
<head><link rel="stylesheet" href="style.css"><title>Products</title></head>
<body>
    <header>
    <h1>Khairdin Restaurant</h1>
    <nav>
        <a href="index.php">Home</a> |
        <a href="manage_categories.php">Categories</a> |
        <a href="manage_products.php">Products</a> |
        <a href="place_order.php">Order</a> |
        <a href="view_bills.php">Bills</a>
    </nav>
</header>
<h2 style="text-align:center;">Manage Products</h2>
<body>

<form method="post">
    <input name="name" required>
    <select name="category"><?php while($c = $categories->fetch_assoc()): ?><option value="<?= $c['id'] ?>"><?= $c['name'] ?></option><?php endwhile; ?></select>
    <input name="price" type="number"  required>
    <button>Add Product</button>
</form>
<table><tr><th>ID</th><th>Name</th><th>Category</th><th>Price</th></tr>
<?php while ($row = $products->fetch_assoc()): ?>
<tr><td><?= $row['id'] ?></td><td><?= $row['name'] ?></td><td><?= $row['category'] ?></td><td>$<?= $row['price'] ?></td></tr>
<?php endwhile; ?></table>
<footer>
    <p>&copy; <?php echo date('Y'); ?> Khairdin Restaurant. All rights reserved.</p>
</footer>
</body>
</html>

