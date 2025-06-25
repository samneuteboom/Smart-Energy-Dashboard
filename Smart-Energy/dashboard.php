<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head><link rel="stylesheet" href="css/dashboard.css"></head>
<body>
<nav>
    
</nav>
<h1>Welcome, <?= isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Guest' ?>!</h1>
<head>
  
  <body>
    <nav class="navbar">
   <div class="logo">
        <img src="images/logo.png" alt="Logo">
    </div>
    </div>
    
    <div class="off-screen-menu">
      <ul>
        <li><a href="index.php">Home</a></li>
          <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="account.php">Account</a></li>
        <li><a href="logout.php">Logout</a></li>
    
    
 
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
;</script>
</html>