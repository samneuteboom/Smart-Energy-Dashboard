<?php
// Start de PHP sessie - dit is nodig om sessievariabelen te kunnen gebruiken/verwijderen
session_start();

// Vernietig alle data die in de huidige sessie is opgeslagen
// Dit verwijdert alle $_SESSION variabelen en beëindigt de sessie
session_destroy();

// Stuur de gebruiker door naar de loginpagina
// header() stuurt een raw HTTP header - Location: zorgt voor een redirect
header('Location: login.php');

// Zorg ervoor dat er geen verdere code wordt uitgevoerd na de redirect
exit();