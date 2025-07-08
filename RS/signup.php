<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Signup</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php require "header.php";?>
<h2 style="text-align: center;">Signup</h2>
<form method="post">
    <input type="text" name="name" placeholder="Name" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="text" name="mobile" placeholder="Mobile" required>
    <input type="text" name="address" placeholder="Address" required>
    <input type="password" name="password" placeholder="Password" required>
    <input type="text" name="secQ" placeholder="Security Question" required>
    <input type="text" name="answer" placeholder="Answer" required>
    <button type="submit">Signup</button>
</form>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include 'config.php';
    $stmt = $conn->prepare("INSERT INTO user (name, email, mobileNumber, address, password, securityQuestion, answer, status) VALUES (?, ?, ?, ?, ?, ?, ?, 'false')");
    $stmt->bind_param("sssssss", $_POST['name'], $_POST['email'], $_POST['mobile'], $_POST['address'], $_POST['password'], $_POST['secQ'], $_POST['answer']);
    echo $stmt->execute() ? "<p style='color:green;text-align:center;'>Registered successfully</p>" : "<p style='color:red;'>Error: {$stmt->error}</p>";
}
?>
<?php require "footer.php";?>
</body>
</html>