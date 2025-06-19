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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/registreren.css">
</head>
<body>
    <h1>Registratie</h1>
    <form>
        <label for="e-mail">E-mailadres</label><br>
        <input type="text" name="e-mail" id="e-mail"><br>
        <label for="Gebruikersnaam">Gebruikersnaam</label><br>
        <input type="text" name="Gebruikersnaam" id="Gebruikersnaam"><br>
        <label for="Wachtwoord">Wachtwoord</label><br>
        <input type="text" name="Wachtwoord" id="Wachtwoord"><br>
        <label for="WachtwoordHerhalen">Wachtwoord herhalen</label><br>
        <input type="text" name="WachtwoordHerhalen" id="WachtwoordHerhalen"><br>
    </form>
    <p>heb je al een account? klik <a href="login.html">Hier</a></p>
    <button type="submit">Registreren</button>
</body>
</html>