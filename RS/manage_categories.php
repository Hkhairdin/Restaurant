<?php
// File: manage_categories.php
include 'config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'])) {
    $stmt = $conn->prepare("INSERT INTO category (name) VALUES (?)");
    $stmt->bind_param("s", $_POST['name']);
    $stmt->execute();
    header("Location: manage_categories.php");
    exit;
}

if (isset($_GET['delete'])) {
    $stmt = $conn->prepare("DELETE FROM category WHERE id = ?");
    $stmt->bind_param("i", $_GET['delete']);
    $stmt->execute();
    header("Location: manage_categories.php");
    exit;
}

if (isset($_POST['reset_all'])) {
    $conn->query("TRUNCATE TABLE category");
    header("Location: manage_categories.php");
    exit;
}

$categories = $conn->query("SELECT * FROM category");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Categories</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .delete-btn {
            background-color:black;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .delete-btn:hover {
            background-color:gray;
        }
      
        .reset-btn {
            
            color: white;
            padding: 8px 14px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            background: #c59d5f;
             color: #fff;
  
  font-weight: bold;
  
            margin-top: 15px;
        }
    </style>
</head>
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
<main>
<h2 style="text-align:center;">Manage Categories</h2>


<form method="post">
    <input type="text" name="name" placeholder="Category Name" required>
    <button type="submit" >Add Category</button><button type="submit"  style="text-align:center"name="reset_all" class="reset-btn" onclick="return confirm('Are you sure you want to delete all categories and reset IDs?');">
        🔁 Reset All Categories
    </button>
</form>




<?php if ($categories->num_rows > 0): ?>
<table>
    <tr>
        <th>ID</th>
        <th>Category Name</th>
        <th>Action</th>
    </tr>
    <?php while ($row = $categories->fetch_assoc()): ?>
    <tr>
        <td><?php echo $row['id']; ?></td>
        <td><?php echo $row['name']; ?></td>
        <td>
            <form method="get" style="display:inline" onsubmit="return confirm('Are you sure you want to delete this category?');">
                <input type="hidden" name="delete" value="<?php echo $row['id']; ?>">
                <button type="submit" class="delete-btn">Delete</button>
            </form>
        </td>
    </tr>
    <?php endwhile; ?>
</table>


<?php else: ?>
<p style="text-align:center; color:orange; font-weight:bold;">No categories available. Please add one above.</p>
<?php endif; ?>
</main>
<footer>
    <p>&copy; <?php echo date('Y'); ?> Khairdin Restaurant. All rights reserved.</p>
</footer>
</body>
</html>
