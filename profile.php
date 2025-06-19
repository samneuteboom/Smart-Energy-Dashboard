<?php
include 'config.php'; // Verbind met database en start sessie
include 'header.php'; // Toon navigatiebalk

// Check of gebruiker is ingelogd
if (!isset($_SESSION['user'])) {
    echo "<p>Je bent niet ingelogd. <a href='login.php'>Log in</a></p>";
    exit;
}

echo "<h2>Profielpagina</h2>";

// Toon welkomsttekst afhankelijk van de rol
if ($_SESSION['user']['role'] === 'admin') {
    echo "<p>Hallo admin " . htmlspecialchars($_SESSION['user']['username']) . "</p>";
} else {
    echo "<p>Hallo gebruiker " . htmlspecialchars($_SESSION['user']['username']) . "</p>";
}
?>

<!-- Link om uit te loggen -->
<p><a href="logout.php">Uitloggen</a></p>
