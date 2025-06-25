<?php
// Database configuratie bestand inladen
require 'db.php';

// Variabele voor berichten naar de gebruiker
$message = '';

// Controleer of het formulier is verzonden (POST request)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Haal de formuliergegevens op
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];

    // Controleer of wachtwoorden overeenkomen
    if ($password === $confirm) {
        // Hash het wachtwoord voor veilige opslag
        $hash = password_hash($password, PASSWORD_DEFAULT);
        
        // Bereid de SQL query voor om gebruiker toe te voegen
        $stmt = $pdo->prepare("INSERT INTO users (email, username, password) VALUES (?, ?, ?)");
        
        // Voer de query uit met de gebruikersgegevens
        if ($stmt->execute([$email, $username, $hash])) {
            // Succesbericht en doorsturen naar login pagina
            $message = 'Registration successful!';
            header("Location: login.php");
            exit();
        } else {
            // Foutmelding als query mislukt
            $message = 'Registration failed.';
        }
    } else {
        // Foutmelding als wachtwoorden niet overeenkomen
        $message = 'Passwords do not match.';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <!-- CSS stylesheet voor registratiepagina -->
    <link rel="stylesheet" type="text/css" href="./css/register.css">
</head>
<body>
    <!-- Registratie formulier container -->
    <div class="login-container">
    <a href="index.php">
        <img src="images/logo.png" alt="Logo" class="logo">
    </a>
    
    <div class="login-header">
        <div class="login-title">Register</div>
        <div class="login-subtitle">a a n m e l d e n</div>
    </div>
    
    <form method="POST" class="form-container">
        <div class="input-group">
            <label class="input-label">Email:</label>
            <input type="text" name="email" class="input-field" required>
        </div>
        
        <div class="input-group">
            <label class="input-label">Username:</label>
            <input type="text" name="username" class="input-field" required>
        </div>
        
        <div class="input-group">
            <label class="input-label">Password:</label>
            <input type="password" name="password" class="input-field" required>
        </div>
        
        <div class="input-group">
            <label class="input-label">Confirm Password:</label>
            <input type="password" name="confirm" class="input-field" required>
        </div>
        
        <button class="login-button" type="submit">Register</button>
        
        <p class="footer-text">Heb je al een account? <a href="login.php">Login hier</a></p>
    </form>
</div>
        <!-- Toon eventuele berichten (foutmeldingen/success) -->
        <p><?= htmlspecialchars($message) ?></p>
    </div>
    
</body>
</html>