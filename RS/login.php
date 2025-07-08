<?php
session_start();
include 'config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    // 1) Fetch only the fields we need
    $stmt = $conn->prepare("SELECT id, password, status FROM user WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        $storedHash = $user['password'];

        // 2) Check if it's a valid hash
        if (password_verify($password, $storedHash)) {
            // OK: password matches the hash
            $loginOk = true;

        } elseif ($password === $storedHash) {
            // Legacy case: password in database was plain-text
            // Treat this as a successful login, then re-hash & store it
            $loginOk = true;
            $newHash = password_hash($password, PASSWORD_DEFAULT);

            $upd = $conn->prepare("UPDATE user SET password = ? WHERE id = ?");
            $upd->bind_param("si", $newHash, $user['id']);
            $upd->execute();
            // no need to check, but you could catch errors here
        }

        if (!empty($loginOk)) {
            // 3) Set sessions & redirect
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role']    = ($user['status'] === 'true') ? 'admin' : 'user';
            header('Location: dashboard.php');
            exit;
        }

        // If we get here, password failed both tests
        $error = 'Invalid password.';
    } else {
        $error = 'User not found.';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php require "header.php"; ?>

    <h2 style="text-align: center;">Login</h2>
    <?php if ($error): ?>
        <p style="color:red; text-align:center;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form method="post" style="max-width:320px; margin:20px auto;">
        <input type="email"    name="email"    placeholder="Email"    required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>

    <?php require "footer.php"; ?>
</body>
</html>
