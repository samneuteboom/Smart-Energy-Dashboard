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
    <link rel="stylesheet" href="css/register.css">
</head>
<body>
    <!-- Registratie formulier container -->
    <div class="login-container">
        <!-- Titel van het formulier -->
        <div class="login-header">Register</div>
        
        <!-- Registratie formulier -->
        <form method="POST">
            <!-- Email invoerveld -->
            <label>Email:</label>
            <input type="text" name="email" required>
            
            <!-- Gebruikersnaam invoerveld -->
            <label>Username:</label>
            <input type="text" name="username" required>
            
            <!-- Wachtwoord invoerveld -->
            <label>Password:</label>
            <input type="password" name="password" required>
            
            <!-- Wachtwoord bevestiging invoerveld -->
            <label>Confirm Password:</label>
            <input type="password" name="confirm" required>
            
            <!-- Registratie knop -->
            <button class="login-button" type="submit">Register</button>
            
            <!-- Link naar login pagina voor bestaande gebruikers -->
            <p class="link">Heb je al een account? <a href="login.php">Klik hier</a></p>
        </form>
        
        <!-- Toon eventuele berichten (foutmeldingen/success) -->
        <p><?= htmlspecialchars($message) ?></p>
    </div>
</body>
</html>