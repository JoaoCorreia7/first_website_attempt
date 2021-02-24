<?php
    include('../includes/config.php');
    include('../includes/functions.php');

    $description = htmlentities($_POST['description']);
    $price = htmlentities($_POST['price']);
    $stock = $_POST['stock'];
    $id = $_POST["id"];

    $conn = dbconn();

    $result = pg_prepare($conn, "query", "UPDATE product SET description=$1, price=$2, stock={$stock} WHERE product_id={$id}");
    $result = pg_execute($conn, "query", array("{$description}", "{$price}"));
    
    $_SESSION['changes'] = true;
    header("Location: ../main.php?page=product_detail&id=".$id."");
?>