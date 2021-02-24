<?php
$order_code = $_GET['order_code'];
if (isset($_GET['url'])) $url = $_GET['url'];
?>

<?php
    if (isset($_SESSION['changes']) && $_SESSION['changes'] == true){
        $_SESSION['changes'] = NULL;
        alert("Changes made successfully");
    }
?>

<div class="greenline"></div>
<div class="account_main_container">
    <div class="account_side_panel"></div>
        <div class="account_middle_container">
            <div class="account_grey_box">
                <h1 class="account_headers">Order #<?php echo $order_code?>:</h1>
                <?php
                $conn = dbconn();
                $query = "SELECT name, final_price, quantity, CAST(time AS DATE) as date, status FROM cart INNER JOIN product ON cart.product_id = product.product_id WHERE order_code='{$order_code}' AND is_bought";
                $result = pg_exec($query);
                $row = pg_fetch_assoc($result);
                $total = 0;
                $date = $row['date'];
                $status = $row['status'];
                while(isset($row['name'])){
                    echo '<p class="admin_text">--- '.$row['name'].' --- Price per unit: $'.$row['final_price'].' --- Quantity: '.$row['quantity'].'</p><br>';
                    $total = $total + floatval($row['final_price']) * $row['quantity'];
                    $row = pg_fetch_assoc($result);
                }
                echo '<br><p class="admin_text">Total: $'.$total.'</p><br>';
                echo '<p class="admin_text">Date: '.$date.'</p><br><br>';
                echo '<p class="admin_text">Status: </p><br>';
                ?>
                <form action="actions/change_order_status.php" method="post">
                    <label class="admin_text"> 
                        <input type="radio" name="status" value="In progress" <?php if (strcmp($status, 'In progress') == 0) echo "checked"?>>
                        In progress
                    </label><br>
                    <label class="admin_text"> 
                        <input type="radio" name="status" value="Shipped" <?php if (strcmp($status, 'Shipped') == 0) echo "checked"?>>
                        Shipped
                    </label><br><br>
                    <input type="hidden" name="order_code" value="<?php echo $order_code ?>"> 
                    <input type="hidden" name="url" value="<?php echo $url ?>"> 
                    <input type="submit" class="order_details_button" value="Save Changes"><br><br>
                </form>
                <?php if(isset($url)){ ?>
                <button class="order_details_button"><a href="main.php?<?php echo $url?>">Return to previous page</a></button>
                <?php } ?>
            </div>
        </div>
    <div class="account_side_panel"></div>
</div>


