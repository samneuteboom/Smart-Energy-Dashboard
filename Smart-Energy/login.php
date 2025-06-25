<?php
require 'db.php';

// Verbeterde sessie-start
if (session_status() === PHP_SESSION_NONE) {
    session_start([
        'cookie_lifetime' => 86400,
        'cookie_secure' => isset($_SERVER['HTTPS']),
        'cookie_httponly' => true,
        'cookie_samesite' => 'Lax'
    ]);
}

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']); // Trim whitespace
    $password = $_POST['password'];

    // Input validatie
    if (empty($username) || empty($password)) {
        $message = 'Vul alle velden in';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            session_regenerate_id(true);
            
            header('Location: ' . ($_SESSION['return_url'] ?? 'dashboard.php'));
            exit();
        } else {
            $message = 'Onjuiste gebruikersnaam of wachtwoord';
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="css/login.css">
    
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1 class="login-title">
                <img src="images/logo.png" alt="Logo" class="logo">
            </h1>
        </div>
        
        <div class="form-container">
            <?php if (!empty($message)): ?>
                <div class="error-message"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="input-group">
                    <label class="input-label">Gebruikersnaam</label>
                    <input type="text" name="username" class="input-field" 
                           value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" 
                           placeholder="Voer uw gebruikersnaam" required>
                </div>
                
                <div class="input-group">
                    <label class="input-label">Wachtwoord</label>
                    <input type="password" name="password" class="input-field" 
                           placeholder="Voer uw wachtwoord in" required>
                </div>
                
                <button type="submit" class="login-button">Inloggen</button>
            </form>
            
            <div class="divider">
                <span class="divider-text">OF</span>
            </div>
            
            <p class="footer-text">
                Heb je geen account? <a href="register.php">Registreer nu</a>
            </p>
        </div>
    </div>
</body>
</html>