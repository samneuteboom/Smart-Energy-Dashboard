<?php
require 'db.php';

// Verbeterde sessie-start
if (session_status() === PHP_SESSION_NONE) {
    session_start([
        'cookie_lifetime' => 86400, // 24 uur
        'cookie_secure' => isset($_SERVER['HTTPS']), // Beveiliging voor HTTPS
        'cookie_httponly' => true, // Beveiliging tegen XSS
        'cookie_samesite' => 'Lax' // Beveiliging tegen CSRF
    ]);
}

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // BELANGRIJK: Sla zowel ID als username op
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $username;
        
        // Regenereren sessie-ID voor beveiliging
        session_regenerate_id(true);
        
        // Doorsturen naar waar de gebruiker vandaan kwam of dashboard
        header('Location: ' . ($_SESSION['return_url'] ?? 'dashboard.php'));
        exit();
    } else {
        $message = 'Login gegevens zijn incorrect';
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
    
  </div>
  
  <div class="form-container">
    <form method="POST" action="">
      <div class="input-group">
        <label class="input-label">Gebruikersnaam</label>
        <input type="text" name="username" class="input-field" placeholder="Voer uw gebruikersnaam" required>
      </div>
      
      <div class="input-group">
        <label class="input-label">Wachtwoord</label>
        <input type="password" name="password" class="input-field" placeholder="Voer uw wachtwoord in" required>
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
        <p><?= htmlspecialchars($message) ?></p>
    </div>
</body>
</html>