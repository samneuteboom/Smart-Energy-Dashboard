<?php
require 'auth.php';

if ($auth->isLoggedIn()) {
    header("Location: dashboard.php");
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);

    if ($auth->login($email, $password, $remember)) {
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Ongeldige inloggegevens";
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Energy - Inloggen</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        .container { max-width: 400px; margin: 50px auto; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h1 { color: #2c3e50; text-align: center; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; }
        button { width: 100%; padding: 10px; background: #3498db; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .error { color: #e74c3c; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Smart Energy</h1>
        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label for="email">E-mailadres</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Wachtwoord</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label>
                    <input type="checkbox" name="remember"> Onthoud mij
                </label>
            </div>
            <button type="submit">Inloggen</button>
        </form>
    </div>
</body>
</html>