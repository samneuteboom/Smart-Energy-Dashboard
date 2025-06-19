<?php
include 'config.php'; // Verbind met database en start sessie
include 'header.php'; // Toon navigatiebalk

// Verwerk het inlogformulier als er gepost is
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? ''); // Haal gebruikersnaam op
    $password = $_POST['password'] ?? '';       // Haal wachtwoord op

    if ($username === '' || $password === '') {
        echo "<p>Vul alle velden in.</p>"; // Controleer of alle velden ingevuld zijn
    } else {
        // Zoek gebruiker op in database
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Controleer of gebruiker bestaat en wachtwoord klopt
        if ($user && password_verify($password, $user['password'])) {
            // Sla gebruikersdata op in sessie
            $_SESSION['user'] = [
                'id' => $user['id'],
                'username' => $user['username'],
                'role' => $user['role']
            ];
            header('Location: profile.php'); // Ga naar profielpagina
            exit;
        } else {
            echo "<p>Ongeldige gebruikersnaam of wachtwoord.</p>";
        }
    }
}
?>



<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <div class="login-container">
        <div class="login-header">Login</div>
        <form method="POST" action="">
            <label for="username">Gebruikersnaam</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Wachtwoord</label>
            <input type="password" id="password" name="password" required>

            <p class="link">Heb je nog geen account? Klik <a href="register.php">hier</a></p>

            <button type="submit" class="login-button">Inloggen</button>
        </form>
    </div>
</body>
</html>
