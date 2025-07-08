<?php
// File: place_order.php
include 'config.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$products = $conn->query("SELECT * FROM product");
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product'])) {
    $pid = $_POST['product'];
    $qty = $_POST['quantity'];

    $stmt = $conn->prepare("SELECT price, name FROM product WHERE id = ?");
    $stmt->bind_param("i", $pid);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($product = $result->fetch_assoc()) {
        $total = $product['price'] * $qty;

        $stmt = $conn->prepare("INSERT INTO bill (name, mobileNumber, email, date, total, createdBy) VALUES (?, ?, ?, NOW(), ?, ?)");
        $stmt->bind_param("sssdi", $_POST['name'], $_POST['mobile'], $_POST['email'], $total, $_SESSION['user_id']);
        $stmt->execute();

        $message = "<p style='text-align:center;color:lightgreen;'>Order placed for <strong>{$product['name']}</strong> × {$qty} successfully! Total: $<strong>{$total}</strong></p>";
    } else {
        $message = "<p style='text-align:center;color:red;'>Product not found.</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Place Order</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
    <h1>Khairdin Restaurant</h1>
    <nav>
        <a href="index.php">Home</a> |

        <a href="place_order.php">Order</a> |
        <a href="view_bills.php">Bills</a>
    </nav>
</header>
    
<h2 style="text-align:center;">Place Your Order</h2>
<?= $message ?>
<form method="post">
    <input type="text" name="name" placeholder="Customer Name" required>
    <input type="text" name="mobile" placeholder="Mobile Number" required>
    <input type="email" name="email" placeholder="Email Address" required>
    <select name="product" required>
        <option value="">-- Select Product --</option>
        <?php while($p = $products->fetch_assoc()): ?>
        <option value="<?= $p['id'] ?>"><?= $p['name'] ?> ($<?= $p['price'] ?>)</option>
        <?php endwhile; ?>
    </select>
    <input type="number" name="quantity" placeholder="Quantity" value="1" min="1" required>
    <button type="submit">Submit Order</button>
</form>

<footer>
    <p>&copy; <?php echo date('Y'); ?> Khairdin Restaurant. All rights reserved.</p>
</footer>
