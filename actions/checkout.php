<?php
    include('../includes/config.php');
    include('../includes/functions.php');
    
    dbconn();
    
    $query = $_POST['query'];
    $result = pg_exec($query);
    $row = pg_fetch_assoc($result);
    $order_code = $row['order_code'];
  
    while(isset($row['name'])){
        $stock = getProductById($row['product_id'])['stock'];
        $quantity = $row['quantity'];
        $stock = $stock - $quantity;
        
        $query = "UPDATE product SET stock={$stock} WHERE product_id={$row['product_id']}";
        pg_exec($query);
        
        $query = "UPDATE cart SET time=CURRENT_TIMESTAMP, is_bought='t', final_price='{$row['price']}', status='In progress' WHERE username='{$_SESSION['username']}' AND order_code='{$order_code}' AND product_id={$row['product_id']}";
        pg_exec($query);

        $row = pg_fetch_assoc($result);
    }

?>

<p>Checkout page - Not to be developed in this project - Order has been processed and is now complete</p>
<a href="../main.php?page=home">Return to home page</a>
