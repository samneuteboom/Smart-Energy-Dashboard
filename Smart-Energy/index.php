
<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html>
<head><link rel="stylesheet" href="style_home.css"></head>
<body>
<nav>
    <a href="index.php">Home</a>
    <a href="logout.php">Logout</a>
</nav>
<h1>Welcome, <?= $_SESSION['username'] ?>!</h1>
</body>
</html>
