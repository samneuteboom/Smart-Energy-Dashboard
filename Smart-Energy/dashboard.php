<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head><link rel="stylesheet" href="css/home.css"></head>
<body>
<nav>
    <a href="index.php">Home</a>
    <a href=""></a>
    <a href="logout.php">Logout</a>
</nav>
<h1>Welcome, <?= $_SESSION['username'] ?>!</h1>
</body>
</html>