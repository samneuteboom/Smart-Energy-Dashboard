<?php
include 'config.php'; // Verbind met database en start sessie
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Energie</title>
    <link rel="stylesheet" type="text/css" href="css/homepage.css">
    <link rel="stylesheet" type="text/css" href="css/dashboard.css">
</head>
<body>
<nav class="navbar">
   <div class="logo">
        <img src="images/logo.png" alt="Logo">
    </div>
    </div>
    
    <div class="off-screen-menu">
      <ul>
        <li><a href="index.php">Home</a></li>
          <li><a href="login.php">Login</a></li>
        <li><a href="register.php">Register</a></li>
      </ul>
    </div>

    <nav>
      <div class="ham-menu">
        <span></span>
        <span></span>
        <span></span>
      </div>
    </nav>

    <div class="background">
        <img src="images/green energie.webp" style="display: block; width: 100%;">
        <div class="hero-content">
            <h1>Ontdek Smart Energie</h1>
            <p>Bespaar op uw energiekosten en draag bij aan een groenere toekomst met ons innovatieve energiebeheersysteem.</p>
            <a href="register.php" class="register-btn">Registreer Nu Smart Energie</a>
        </div>
    </div>

    <!-- Add the missing menu overlay element -->
    <div id="menuOverlay" class="menu-overlay"></div>
    
    <script> 
    document.addEventListener('DOMContentLoaded', function() {
        const hamMenu = document.querySelector(".ham-menu");
        const offScreenMenu = document.querySelector(".off-screen-menu");
        const menuOverlay = document.getElementById("menuOverlay");
        const body = document.body;

        // Toggle menu
        const toggleMenu = () => {
            hamMenu.classList.toggle("active");
            offScreenMenu.classList.toggle("active");
            menuOverlay.classList.toggle("active");
            
            if(offScreenMenu.classList.contains('active')) {
                body.style.overflow = 'hidden';
            } else {
                body.style.overflow = '';
            }
        };

        hamMenu.addEventListener("click", function(e) {
            e.stopPropagation();
            toggleMenu();
        });

        // Sluit menu bij klik op overlay of buiten
        menuOverlay.addEventListener('click', toggleMenu);
        document.addEventListener('click', function(e) {
            if(!offScreenMenu.contains(e.target) && e.target !== hamMenu) {
                if(offScreenMenu.classList.contains('active')) {
                    toggleMenu();
                }
            }
        });

        // Voorkom sluiten bij klik in menu
        offScreenMenu.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    });
    </script>
</body>
</html>