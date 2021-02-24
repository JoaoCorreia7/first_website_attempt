<?php
    include('../includes/config.php');
    include('../includes/functions.php');

    $name = htmlentities($_POST["name"]);
    $email = htmlentities($_POST["email"]);
    $address = htmlentities($_POST["address"]);
    $phone = htmlentities($_POST["phone"]);

    $conn = dbconn();

    $result = pg_prepare($conn, "query", "UPDATE client SET name=$1, email=$2, address=$3, phone=$4 WHERE username='{$_SESSION['username']}'");
    $result = pg_execute($conn, "query", array("{$name}", "{$email}", "{$address}", "{$phone}"));
    
    $_SESSION['changes'] = true;
    header("Location: ../main.php?page=client");
?>