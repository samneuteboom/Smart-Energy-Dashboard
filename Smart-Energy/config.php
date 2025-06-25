<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Start of herstart de sessie zodat je gebruikersgegevens kunt opslaan
}

// Database configuratie
$host = "localhost";
$dbname = "login_system";
$user = "root";  // Pas hier je database gebruikersnaam aan
$pass = "";      // Pas hier je database wachtwoord aan

try {
    // Maak een PDO database connectie
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Zet foutmeldingen aan
} catch(PDOException $e) {
    die("Database connectie mislukt: " . $e->getMessage()); // Stop als connectie faalt
}
?>