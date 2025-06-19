<?php
session_start();   // Start sessie om te kunnen vernietigen
session_destroy(); // Vernietig alle sessiegegevens
header('Location: index.php'); // Stuur gebruiker naar homepage
exit;
