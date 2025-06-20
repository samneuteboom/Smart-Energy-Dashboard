<?php
// Database configuratie bestand inladen
require 'db.php';

// Sessie starten om gebruikersgegevens te kunnen onthouden
session_start();

// Variabele voor berichten naar de gebruiker
$message = '';

// Controleer of het formulier is verzonden (POST request)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Haal de ingevoerde gebruikersnaam en wachtwoord op=
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Bereid een SQL query voor om de gebruiker op te halen
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    // Voer de query uit met de ingevoerde gebruikersnaam
    $stmt->execute([$username]);
    // Haal het resultaat op (gebruikersgegevens)
    $user = $stmt->fetch();

    // Controleer of de gebruiker bestaat EN of het wachtwoord klopt
    if ($user && password_verify($password, $user['password'])) {
        // Sla de gebruikersnaam op in de sessie
        $_SESSION['username'] = $username;
        // Stuur door naar het dashboard bij succesvolle login
        header('Location: dashboard.php');
        exit();
    } else {
        // Toon foutmelding bij onjuiste inloggegevens
        $message = 'Login gegevens zijn incorrect';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <!-- CSS stylesheet voor de loginpagina -->
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <!-- Login formulier container -->
    <div class="login-container">
        <!-- Titel van het formulier -->
        <div class="login-header">Login</div>
        
        <!-- Login formulier -->
        <form method="POST">
            <!-- Gebruikersnaam invoerveld -->
            <label>Username:</label>
            <input type="text" name="username" required>
            
            <!-- Wachtwoord invoerveld -->
            <label>Password:</label>
            <input type="password" name="password" required>
            
            <!-- Login knop -->
            <button class="login-button" type="submit">Login</button>
            
            <!-- Link naar registratiepagina voor nieuwe gebruikers -->
            <p class="link">Heb je nog geen account? <a href="register.php">Klik hier</a></p>
        </form>
        
        <!-- Toon eventuele foutmeldingen -->
        <p><?= htmlspecialchars($message) ?></p>
    </div>
</body>
</html>