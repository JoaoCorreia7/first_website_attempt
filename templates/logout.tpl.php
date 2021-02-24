<?php
$_SESSION['isAuth'] = false;
$_SESSION['isAdmin'] = false;
session_destroy();
header("Location: main.php?page=home");
exit();
?>