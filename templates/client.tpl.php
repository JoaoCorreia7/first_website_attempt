<?php

if (isset($_SESSION['changes']) && $_SESSION['changes'] == true){
    $_SESSION['changes'] = NULL;
    alert("Changes made successfully");
}

$count = 0;
$total = 0;
$total2 = 0;
dbconn();

$query = "SELECT name, email, address, phone FROM client WHERE username='{$_SESSION['username']}'";
$result = pg_exec($query);
$row = pg_fetch_assoc($result);

$query2 = "SELECT name, price, order_code, product.product_id AS product_id, quantity FROM cart INNER JOIN product ON cart.product_id = product.product_id WHERE username='{$_SESSION['username']}' AND NOT is_bought ORDER BY product_id";
$result2 = pg_exec($query2);
$row2 = pg_fetch_assoc($result2);

$query3 = "SELECT name, final_price, quantity, order_code, CAST(time AS DATE) as date, status FROM cart INNER JOIN product ON cart.product_id = product.product_id WHERE username='{$_SESSION['username']}' AND is_bought ORDER BY time DESC";
$result3 = pg_exec($query3);
$row3 = pg_fetch_assoc($result3);
?>

<div class="greenline"></div>
<div class="account_main_container">
    <div class="account_side_panel"></div>
    <div class="account_middle_container">
        <div class="account_grey_box">
            <h1 class="account_headers">Personal Information:</h1>
            <form class="account_form" action="actions/save_personal_info.php" method="post">
                <label class="account_text">Name:</label>
                <input class="account_input" type="text" name="name" value="<?php echo $row['name'] ?>">
                <label class="account_text">Email:</label>
                <input class="account_input" type="email" name="email" value="<?php echo $row['email'] ?>">
                <label class="account_text">Address:</label>
                <input class="account_input" type="text" name="address" value="<?php echo $row['address'] ?>">
                <label class="account_text">Phone Number:</label>
                <input class="account_input" type="text" name="phone" value="<?php echo $row['phone'] ?>">
                <input class="account_save_changes_button" type="submit" value="Save changes">
            </form>
            <br><br>
            <h1 class="account_headers">Previous Orders:</h1>
            <?php
                if (!isset($row3['order_code'])){
                    echo '<p class="account_text">[No previous orders]</p>';
                }
                else {
                    $order_code = $row3['order_code'];
                    $prev_order_code = $order_code;
                    while(isset($row3['order_code'])){ // while order codes exist
                        echo '<p class="account_previous_orders_header">Order #'.$order_code.' --- Date: '.$row3['date'].' --- Status: '.$row3['status'].'</p>';
                        while($order_code == $prev_order_code){ // while information within the same order exists
                            $prev_order_code = $row3['order_code'];
                            echo '<p class="account_small_text">--- '.$row3['name'].' - Quantity: '.$row3['quantity'].'</p>';
                            $total = $total + floatval($row3['final_price']) * $row3['quantity'];
                            $row3 = pg_fetch_assoc($result3);
                            $order_code = $row3['order_code'];
                        }
                        echo '<p class="account_small_text">Total: $'.$total.'</p>';
                        echo '<div class="account_blackline"></div>';
                        $prev_order_code = $order_code;
                        $total = 0;
                    }
                }
            ?>
        </div>
        <div class="account_middle_column"></div>
        <div class="account_grey_box">
            <h1 class="account_headers">Cart:</h1>
            <?php
                if (isset($row2['name'])){
                    echo '<form action="actions/save_cart_changes.php" method="post">';
                    while(isset($row2['name'])){
                        $count++;
                        $stock = getProductById($row2['product_id'])['stock'];
                        $quantity = $row2['quantity'];
                        $total2 = $total2 + floatval($row2['price']) * $quantity;
                        ?>
                        <p class="account_text"> <?php echo $row2['name'] ?> - Quantity:</p>
                        <input type="number" class="account_quantity" name="<?php echo 'item'.$count.'_quantity' ?>" min="0" max="<?php echo $stock ?>" value="<?php echo $quantity ?>"><br>
                        <input type="hidden" name="<?php echo 'item'.$count.'_id' ?>" value="<?php echo $row2['product_id'] ?>">
                        <?php
                        $row2 = pg_fetch_assoc($result2);
                    }
                    ?>
                    <input type="hidden" name="count" value="<?php echo $count ?>">
                    <br><p class="account_text">Total: $<?php echo $total2 ?></p>
                    <input class="account_save_changes_button" type="submit" value="Save changes">
                    </form>
                    <?php
                }
                else echo '<p class="account_text">[Empty]</p>'
            ?>
            <br><br><br><br>
            <form action="actions/checkout.php" method="post">
                <input type="submit" class="checkout_button zoom" value="Proceed to Checkout" />
                <input type="hidden" name="query" value="<?php echo $query2 ?>">
            </form>
        </div>
    </div>
    <div class="account_side_panel"></div>
</div>