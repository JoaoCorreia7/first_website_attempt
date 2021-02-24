<?php
    include('../includes/config.php');
    include('../includes/functions.php');

    $order_code = $_POST['order_code'];
    $status = $_POST['status'];
    $url = rawurlencode($_POST['url']);


    $conn = dbconn();
    
    $result = pg_prepare($conn, "query", "UPDATE cart SET status=$1 WHERE order_code=$2");
    $result = pg_execute($conn, "query", array($status, $order_code));
    $row = pg_fetch_assoc($result);

    $_SESSION['changes'] = true;
    header("Location: ../main.php?page=order_details&order_code={$order_code}&url={$url}");
?>