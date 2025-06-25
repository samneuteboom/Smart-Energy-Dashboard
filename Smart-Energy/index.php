<?php

include 'config.php'; // Verbind met database en start sessie
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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
</body>


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
