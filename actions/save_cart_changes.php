<?php
    include('../includes/config.php');
    include('../includes/functions.php');
    
    dbconn();
    $count = $_POST['count'];
    
    for ($i=1; $i<=$count; $i++){
        $new_quantity = $_POST['item'.$i.'_quantity'];
        $productId = $_POST['item'.$i.'_id'];
        
        $query = "UPDATE cart SET quantity={$new_quantity} WHERE username='{$_SESSION['username']}' AND product_id={$productId} AND NOT is_bought";
        $result = pg_exec($query);
        
        if ($new_quantity == 0) {
            $query = "DELETE FROM cart WHERE quantity=0";
            $result = pg_exec($query);
        }

    }

    $_SESSION['changes'] = true;
    header("Location: ../main.php?page=client");
?>