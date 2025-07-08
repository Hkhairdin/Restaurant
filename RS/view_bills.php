<?php
// File: view_bills.php
include 'config.php';
session_start();

// require login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$uid  = (int) $_SESSION['user_id'];
$role = $_SESSION['role'] === 'admin' ? 'admin' : 'user';

// select bills
if ($role === 'admin') {
    $stmt = $conn->prepare("SELECT * FROM bill ORDER BY id ASC");
} else {
    $stmt = $conn->prepare("SELECT * FROM bill WHERE createdBy = ? ORDER BY id ASC");
    $stmt->bind_param("i", $uid);
}
$stmt->execute();
$bills = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>View Bills</title>
  <link rel="stylesheet" href="style.css">
  <style>
    /* small centering tweak */
    .back-btn { margin: 20px; }
    table { width: 90%; margin: 0 auto 40px; border-collapse: collapse; }
    th, td { padding: 8px 12px; border: 1px solid #ccc; text-align: center; }
    th { background: #f4f4f4; }
  </style>
</head>
<body>

  <h2 style="text-align:center;">View Bills</h2>

  <!-- Go Back button -->
  <div class="back-btn" style="text-align:center;">
    <button onclick="history.back();" class="nav-btn">
      ← Go Back
    </button>
  </div>

  <table>
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>Mobile</th>
      <th>Email</th>
      <th>Date</th>
      <th>Total</th>
    </tr>

    <?php while ($b = $bills->fetch_assoc()): ?>
    <tr>
      <td><?php echo htmlspecialchars($b['id']); ?></td>
      <td><?php echo htmlspecialchars($b['name']); ?></td>
      <td><?php echo htmlspecialchars($b['mobileNumber']); ?></td>
      <td><?php echo htmlspecialchars($b['email']); ?></td>
      <td><?php echo htmlspecialchars($b['date']); ?></td>
      <td>$<?php echo htmlspecialchars($b['total']); ?></td>
    </tr>
    <?php endwhile; ?>

  </table>

</body>
</html>
