<?php
    include('../includes/config.php');
    include('../includes/functions.php');

if ($_SESSION['isAuth'] == 0) header("Location: ../main.php?page=login");
else {
    $productId = $_POST["id"];
    $quantity = $_POST["quantity"];
    $order_code = NULL;
    $exists_flag = 0;

    $conn = dbconn();

    $query = "SELECT order_code, product_id, quantity FROM cart WHERE username='{$_SESSION['username']}' AND NOT is_bought";
    $result = pg_exec($query);
    $row = pg_fetch_assoc($result);

    if (!isset($row['order_code'])){
        // create a new cart if there is no active cart
        $order_code = rand(1, 99999999);
        $order_code = str_pad($order_code, 8, '0', STR_PAD_LEFT); 
        
        $query2 = "SELECT order_code FROM cart WHERE order_code={$order_code}";
        $result2 = pg_exec($query2);
        $row2 = pg_fetch_assoc($result2);

        while (isset($row2['order_code'])){ // repeat in case of collision
            $order_code = rand(1, 99999999);
            $order_code = str_pad($order_code, 8, '0', STR_PAD_LEFT); 

            $query2 = "SELECT order_code FROM cart WHERE order_code={$order_code}";
            $result2 = pg_exec($query2);
            $row2 = pg_fetch_assoc($result2);
        }
    }
    else {
        // use the active cart instead
        $order_code = $row['order_code'];
    }

    while (isset($row['order_code'])){
        if ($row['product_id'] == $productId){
            // if the same product already exists on the cart
            $new_quantity = $row['quantity'] + $quantity;
            $stock = getProductById($productId)['stock'];
            if ($new_quantity > $stock){
                $new_quantity = $stock;
            }
            $query = "UPDATE cart SET quantity={$new_quantity} WHERE order_code='{$order_code}' AND product_id={$productId}";
            pg_exec($query);
            $exists_flag = 1;
            break;
        }
        $row = pg_fetch_assoc($result);
    }
    if ($exists_flag == 0){
        // if the product does not exist on the active cart or if there is no active cart yet
        $query = "INSERT INTO cart(order_code, username, product_id, quantity, is_bought) 
            VALUES ('{$order_code}', '".$_SESSION['username']."', '{$productId}', '{$quantity}', 'f')";
        $result = pg_exec($query);
    }

    header("Location: ../main.php?page=products_list");
}
?>