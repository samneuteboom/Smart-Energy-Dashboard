<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}
include 'config.php'; // Verbind met database en start sessie
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/homepage.css">
    
</head>
<body>
<nav class="navbar">
    <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="login.php">Inloggen</a>
        <a href="register.php">Registreren</a>
    </div>
    
    <div class="logo">
        <img src="images/logo.png" alt="Logo">
    </div>

    <div class="profiel">
        <a href="profile.php">
            <img src="images/p2.png" alt="Profiel">
        </a>
        
    </div>
</nav>

    <div class="background">
        <img src="images/green energie.webp" style="display: block; width: 100%;">
        <div class="text">
            <p> Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                 Vivamus lacinia odio vitae vestibulum vestibulum.
                  Cras venenatis euismod malesuada. Sed euismod, 
                  nisl at convallis placerat, 
                  justo purus fermentum massa,
                  non tincidunt arcu elit a nisi. 
                  Nulla facilisi. Maecenas pretium,
                   lorem at cursus feugiat, metus sem facilisis mauris,
                    in suscipit orci lorem ut nisl. 
                    Integer in erat ac augue tincidunt fermentum. Curabitur vel pulvinar elit. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.
<p>
        </div>
    </div>
    
</body>
</html>
