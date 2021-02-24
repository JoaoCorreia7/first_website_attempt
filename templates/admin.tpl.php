<?php

if (isset($_GET['n_results'])) $_SESSION['n_results'] = intval($_GET['n_results']);
else if (!isset($_SESSION['n_results'])) $_SESSION['n_results'] = 20;

if (isset($_GET['start_date'])) $start_date = $_GET['start_date'];
if (!isset($start_date) || empty($start_date)) $start_date = date('Y-m-d', time() - (7*24*60*60));  // default start date = last week
if (isset($_GET['end_date'])) $end_date = $_GET['end_date'];
if (!isset($end_date) || empty($end_date)) $end_date = date('Y-m-d');                               // default end date = now

if (isset($_GET['order'])){
    $order = htmlentities($_GET['order']);
    $email = htmlentities($_GET['email']);
    $name = htmlentities($_GET['name']);
    $username = htmlentities($_GET['username']);
    $phone = htmlentities($_GET['phone']);
    $status = htmlentities($_GET['status']);
    $date = htmlentities($_GET['date']);
    $personalised_search_query = "SELECT order_code, MIN(email) as email, MIN(name) as name, MIN(client.username) as username, MIN(phone) as phone, MIN(status) as status, MIN(CAST(time AS DATE)) as date
                                FROM cart INNER JOIN client ON cart.username = client.username
                                WHERE order_code LIKE $1
                                AND email LIKE $2
                                AND name LIKE $3
                                AND client.username LIKE $4
                                AND phone LIKE $5
                                AND status LIKE $6
                                AND CAST(time AS TEXT) LIKE $7
                                AND is_bought
                                GROUP BY order_code ";
    $orderBy = "ORDER BY date DESC";
}
else {
    $default_search_query = "SELECT order_code, MIN(email) as email, MIN(name) as name, MIN(client.username) as username, MIN(phone) as phone, MIN(status) as status, MIN(CAST(time AS DATE)) as date
                            FROM cart INNER JOIN client ON cart.username = client.username
                            WHERE is_bought
                            GROUP BY order_code 
                            ORDER BY date DESC 
                            LIMIT {$_SESSION['n_results']}";
}

$conn = dbconn();

?>

<div class="greenline"></div>
<div class="account_main_container">
    <div class="account_side_panel"></div>
    <div class="account_middle_container">
        <div class="account_grey_box">
            <h1 class="account_headers">Orders:</h1>
            <form>
            <input type="hidden" name="start_date" value="<?php echo $start_date; ?>">
            <input type="hidden" name="end_date" value="<?php echo $end_date; ?>">  
            <input type="submit" style="display:none">
            <input type="hidden" name="page" value="admin">
            <table>
                <tr>
                    <th>#</th>
                    <th>Order Code</th>
                    <th>Email</th>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Phone Number</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
                <tr>
                    <td class="filter_cell"></td>
                    <td class="filter_cell"><input class="filter_input" type="text" name="order" 
                    <?php 
                        if (!isset($order) || empty($order)) echo 'placeholder="Filter by Order"';
                        else echo 'value="'.$order.'"';                    
                        ?>></td>
                    <td class="filter_cell"><input class="filter_input" type="text" name="email" 
                    <?php 
                        if (!isset($email) || empty($email)) echo 'placeholder="Filter by Email"';
                        else echo 'value="'.$email.'"';
                        ?>></td>
                    <td class="filter_cell"><input class="filter_input" type="text" name="name" 
                    <?php 
                        if (!isset($name) || empty($name)) echo 'placeholder="Filter by Name"';
                        else echo 'value="'.$name.'"';                    
                        ?>></td>
                    <td class="filter_cell"><input class="filter_input" type="text" name="username" 
                    <?php 
                        if (!isset($username) || empty($username)) echo 'placeholder="Filter by Username"';
                        else echo 'value="'.$username.'"';                    
                        ?>></td>
                    <td class="filter_cell"><input class="filter_input" type="text" name="phone"
                    <?php 
                        if (!isset($phone) || empty($phone)) echo 'placeholder="Filter by Phone Number"';
                        else echo 'value="'.$phone.'"';                        
                        ?>></td>
                    <td class="filter_cell"><input class="filter_input" type="text" name="status"
                    <?php 
                        if (!isset($status) || empty($status)) echo 'placeholder="Filter by Status"';
                        else echo 'value="'.$status.'"';                      
                        ?>></td>
                    <td class="filter_cell"><input class="filter_input" type="text" name="date"
                    <?php 
                        if (!isset($date) || empty($date)) echo 'placeholder="Filter by Date"';
                        else echo 'value="'.$date.'"';                     
                        ?>></td>
                    
                </tr>
                <?php
                if (isset($order)){
                    $result = pg_prepare($conn, "query", $personalised_search_query.$orderBy." LIMIT ".$_SESSION['n_results']);
                    $result = pg_execute($conn, "query", array('%'.$order.'%', '%'.$email.'%', '%'.$name.'%', '%'.$username.'%', '%'.$phone.'%', '%'.$status.'%', '%'.$date.'%'));
                }
                else {
                    $result = pg_exec($default_search_query);
                }
                
                $count = 1;
                while ($row = pg_fetch_assoc($result)){
                    echo '<tr>';
                    echo '<td>'.$count.'</td>';
                    echo '<td><a href="main.php?page=order_details&order_code='.$row['order_code'].'&url='.rawurlencode($_SERVER['QUERY_STRING']).'">#'.$row['order_code'].'</a></td>';
                    echo '<td>'.$row['email'].'</td>';
                    echo '<td>'.$row['name'].'</td>';
                    echo '<td>'.$row['username'].'</td>';
                    echo '<td>'.$row['phone'].'</td>';
                    echo '<td>'.$row['status'].'</td>';
                    echo '<td>'.$row['date'].'</td>';
                    echo '</tr>';
                    $count++;
                }
                ?>  
            </table>
            </form>
            <div class="blackline" style="height:3px;margin-bottom:10px;"></div>
            <form style="float:right;">
                <input type="hidden" name="page" value="admin">
                <?php if (isset($order)){ ?>
                    <input type="hidden" name="order" value="<?php echo $order; ?>">
                    <input type="hidden" name="email" value="<?php echo $email; ?>">
                    <input type="hidden" name="name" value="<?php echo $name; ?>">
                    <input type="hidden" name="username" value="<?php echo $username; ?>">
                    <input type="hidden" name="phone" value="<?php echo $phone; ?>">
                    <input type="hidden" name="status" value="<?php echo $status; ?>">
                    <input type="hidden" name="date" value="<?php echo $date; ?>">
                    <input type="hidden" name="start_date" value="<?php echo $start_date; ?>">
                    <input type="hidden" name="end_date" value="<?php echo $end_date; ?>">
                <?php } ?>
                <label class="admin_text">Maximum number of results: </label>
                <input class="admin_quantity" type="number" name="n_results" min="0" value="<?php echo $_SESSION['n_results']; ?>">
                <input type="submit" style="display:none">
            </form>
            <h1 class="account_headers">Bussiness Information: </h1>
            <form>
                <input type="hidden" name="page" value="admin">
                <?php if (isset($order)){ ?>
                    <input type="hidden" name="order" value="<?php echo $order; ?>">
                    <input type="hidden" name="email" value="<?php echo $email; ?>">
                    <input type="hidden" name="name" value="<?php echo $name; ?>">
                    <input type="hidden" name="username" value="<?php echo $username; ?>">
                    <input type="hidden" name="phone" value="<?php echo $phone; ?>">
                    <input type="hidden" name="status" value="<?php echo $status; ?>">
                    <input type="hidden" name="date" value="<?php echo $date; ?>">
                <?php } ?>
                <p class="admin_text"> Between </p>
                <input class="admin_date_submit" type="text" name="start_date" placeholder="yyyy-mm-dd" value="<?php echo $start_date; ?>">
                <p class="admin_text"> and </p>
                <input class="admin_date_submit" type="text" name="end_date" placeholder="yyyy-mm-dd" value="<?php echo $end_date; ?>">
                <p class="admin_text"> : </p>
                <input type="submit" style="display:none">
            </form>
            <?php
            $result = pg_prepare($conn, "q1", "SELECT SUM(CAST(final_price AS FLOAT)*quantity) as revenue FROM cart WHERE is_bought AND CAST(time AS DATE) BETWEEN $1 AND $2");
            $result = pg_execute($conn, "q1", array($start_date, $end_date));
            $value = pg_fetch_assoc($result);
            echo '<p class="admin_text">--- Total revenue: $'.$value['revenue'].'</p><br>';

            $result = pg_prepare($conn, "q2", "SELECT COUNT(DISTINCT order_code) as n_orders FROM cart WHERE is_bought AND CAST(time AS DATE) BETWEEN $1 AND $2");
            $result = pg_execute($conn, "q2", array($start_date, $end_date));
            $value = pg_fetch_assoc($result);
            echo '<p class="admin_text">--- Total number of orders: '.$value['n_orders'].'</p><br>';

            $result = pg_prepare($conn, "q3", "SELECT CAST(time AS DATE) as date, SUM(CAST(final_price AS FLOAT)*quantity) as revenue FROM cart WHERE is_bought AND CAST(time AS DATE) BETWEEN $1 AND $2 GROUP BY CAST(time AS DATE) ORDER BY revenue DESC LIMIT 1");
            $result = pg_execute($conn, "q3", array($start_date, $end_date));
            $value = pg_fetch_assoc($result);
            echo '<p class="admin_text">--- Day with the highest revenue: '.$value['date'].' ($'.$value['revenue'].')</p><br>';

            $result = pg_prepare($conn, "q4", "SELECT CAST(time AS DATE) as date, COUNT(DISTINCT order_code) as n_orders FROM cart WHERE is_bought AND CAST(time AS DATE) BETWEEN $1 AND $2 GROUP BY CAST(time AS DATE) ORDER BY n_orders DESC LIMIT 1");
            $result = pg_execute($conn, "q4", array($start_date, $end_date));
            $value = pg_fetch_assoc($result);
            echo '<p class="admin_text">--- Day with the highest number of orders: '.$value['date'].' ('.$value['n_orders'].' orders)</p><br>';

            echo '<br><p class="admin_text">All-time general Information:</p><br><br>';

            $query = "SELECT COUNT(username) as n_users FROM client WHERE is_admin='t'";
            $result = pg_exec($query);
            $value = pg_fetch_assoc($result);
            echo '<p class="admin_text">--- Total number of administrator accounts: '.$value['n_users'].'</p><br>';

            $query = "SELECT COUNT(username) as n_users FROM client WHERE is_admin='f'";
            $result = pg_exec($query);
            $value = pg_fetch_assoc($result);
            echo '<p class="admin_text">--- Total number of normal accounts: '.$value['n_users'].'</p><br>';

            $query = "SELECT SUM(CAST(final_price AS FLOAT)*quantity) as revenue FROM cart WHERE is_bought";
            $result = pg_exec($query);
            $value = pg_fetch_assoc($result);
            echo '<p class="admin_text">--- Total revenue: $'.$value['revenue'].'</p><br>';

            $query = "SELECT COUNT(DISTINCT order_code) as n_orders FROM cart WHERE is_bought";
            $result = pg_exec($query);
            $value = pg_fetch_assoc($result);
            $query = "SELECT COUNT(DISTINCT order_code) as n_progress FROM cart WHERE is_bought AND status='In progress'";
            $result = pg_exec($query);
            $value2 = pg_fetch_assoc($result);
            echo '<p class="admin_text">--- Total number of orders: '.$value['n_orders'].' ('.$value2['n_progress'].' in progress)</p><br>';

            $query = "SELECT CAST(time AS DATE) as date, SUM(CAST(final_price AS FLOAT)*quantity) as revenue FROM cart WHERE is_bought GROUP BY CAST(time AS DATE) ORDER BY revenue DESC LIMIT 1";
            $result = pg_exec($query);
            $value = pg_fetch_assoc($result);
            echo '<p class="admin_text">--- Day with the highest revenue: '.$value['date'].' ($'.$value['revenue'].')</p><br>';

            $query = "SELECT CAST(time AS DATE) as date, COUNT(DISTINCT order_code) as n_orders FROM cart WHERE is_bought GROUP BY CAST(time AS DATE) ORDER BY n_orders DESC LIMIT 1";
            $result = pg_exec($query);
            $value = pg_fetch_assoc($result);
            echo '<p class="admin_text">--- Day with the highest number of orders: '.$value['date'].' ('.$value['n_orders'].' orders)</p><br>';
            ?>
        </div>
    </div>
    <div class="account_side_panel"></div>
</div>