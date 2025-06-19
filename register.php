<?php
include 'config.php'; // Verbind met database en start sessie
include 'header.php'; // Toon navigatiebalk

// Verwerk het registratieformulier als er gepost is
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? ''); // Haal gebruikersnaam op en verwijder spaties
    $password = $_POST['password'] ?? '';       // Haal wachtwoord op

    if ($username === '' || $password === '') {
        echo "<p>Vul alle velden in.</p>"; // Controleer of alle velden ingevuld zijn
    } else {
        // Check of de gebruikersnaam al bestaat
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            echo "<p>Gebruikersnaam bestaat al.</p>";
        } else {
            // Maak een veilig gehasht wachtwoord
            $hash = password_hash($password, PASSWORD_DEFAULT);

            // Voeg nieuwe gebruiker toe aan de database met rol 'user'
            $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            if ($stmt->execute([$username, $hash])) {
                echo "<p>Registratie gelukt. <a href='login.php'>Log nu in</a></p>";
                exit; // Stop script om het registratieformulier niet te tonen na succes
            } else {
                echo "<p>Er ging iets mis.</p>";
            }
        }
    }
}
?>

<h2>Registreren</h2>
<!-- Registratie formulier -->
<form method="POST">
    Gebruikersnaam:<br>
    <input type="text" name="username"><br>
    Wachtwoord:<br>
    <input type="password" name="password"><br><br>
    <button type="submit">Registreren</button>
</form>
