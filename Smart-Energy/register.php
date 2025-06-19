
<?php
require 'db.php';
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];

    if ($password === $confirm) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (email, username, password) VALUES (?, ?, ?)");
        if ($stmt->execute([$email, $username, $hash])) {
            $message = 'Registration successful!';
        } else {
            $message = 'Registration failed.';
        }
    } else {
        $message = 'Passwords do not match.';
    }
}
?>
<!DOCTYPE html>
<html>
<head><link rel="stylesheet" href="style_register.css"></head>
<body>
<div class="login-container">
    <div class="login-header">Register</div>
    <form method="POST">
        <label>Email:</label><input type="text" name="email" required>
        <label>Username:</label><input type="text" name="username" required>
        <label>Password:</label><input type="password" name="password" required>
        <label>Confirm Password:</label><input type="password" name="confirm" required>
        <button class="login-button" type="submit">Register</button>
        <p class="link"><a href="login.php">Login</a></p>
    </form>
    <p><?= $message ?></p>
</div>
</body>
</html>
