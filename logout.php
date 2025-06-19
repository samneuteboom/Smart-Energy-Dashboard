<?php
require 'auth.php';
$auth->logout();
header("Location: login.php");
exit;
?>