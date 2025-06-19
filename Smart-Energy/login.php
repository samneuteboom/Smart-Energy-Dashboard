
<?php
require 'db.php';
session_start();
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['username'] = $username;
        header('Location: index.php');
    } else {
        $message = 'Incorrect credentials';
    }
}
?>
<!DOCTYPE html>
<html>
<head><link rel="stylesheet" href="style_login.css"></head>
<body>
<div class="login-container">
    <div class="login-header">Login</div>
    <form method="POST">
        <label>Username:</label><input type="text" name="username" required>
        <label>Password:</label><input type="password" name="password" required>
        <button class="login-button" type="submit">Login</button>
<p class="link">Heb je nog geen account? <a href="register.php">Klik hier</a></p>

    </form>
    <p><?= $message ?></p>
</div>
</body>
</html>
