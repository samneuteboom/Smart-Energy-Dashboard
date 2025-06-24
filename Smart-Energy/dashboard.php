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

<nav>
    <a href="index.php">Home</a>
    <a href=""></a>
    <a href="logout.php">Logout</a>
</nav>

<h1>Welkom, <?= htmlspecialchars($_SESSION['username']) ?>!</h1>

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

</body>
</html>
