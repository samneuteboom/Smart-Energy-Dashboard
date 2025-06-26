<?php
session_start();
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <!-- Chart.js en PapaParse via CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/papaparse@5.4.1/papaparse.min.js"></script>
    <style>
        .chart-container { margin: 30px 0; }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="logo">
            <img src="images/logo.png" alt="Logo">
        </div>
        <div class="off-screen-menu" id="offScreenMenu">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="account.php">Account</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
        <div class="ham-menu">
            <span></span>
            <span></span>
            <span></span>
        </div>
        <div id="menuOverlay" style="display:none;"></div>
    </nav>

    <h1>Welkom, <?= isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Guest' ?>!</h1>

    <h2>Kies welke grafieken je wilt bekijken:</h2>
    <form id="grafiekSelector">
        <label><input type="checkbox" name="grafiek" value="Zonnepaneelstroom (A)"> Zonnepaneelstroom (A)</label><br>
        <label><input type="checkbox" name="grafiek" value="Waterstofproductie (L/u)"> Waterstofproductie (L/u)</label><br>
        <label><input type="checkbox" name="grafiek" value="Stroomverbruik woning (kW)"> Stroomverbruik woning (kW)</label><br>
    </form>

    <h3>Selecteer grafiektype:</h3>
    <select id="grafiekTypeSelect">
        <option value="line" selected>Lijn</option>
        <option value="bar">Staaf</option>
        <option value="pie">Cirkeldiagram</option>
        <option value="doughnut">Donut</option>
        <option value="radar">Radar</option>
    </select>

    <h3>Selecteer grafiekformaat:</h3>
    <select id="formaatSelect">
        <option value="400">Klein</option>
        <option value="600" selected>Middel</option>
        <option value="800">Groot</option>
    </select><br>
    <br>
    <button id="pasToeBtn">Pas toe</button>

    <div id="grafieken"></div>

    <!-- Laad eigen JS-bestand -->
    <script src="js/dashboard.js"></script>
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
            menuOverlay.style.display = menuOverlay.classList.contains("active") ? "block" : "none";
            body.style.overflow = offScreenMenu.classList.contains('active') ? 'hidden' : '';
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
