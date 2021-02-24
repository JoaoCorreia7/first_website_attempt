<?php

include('includes/config.php');
include('includes/functions.php');

$page = $_GET['page'];
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="./css/styles.css">
    <title>Virtuoso</title>
</head>
<body>

<?php
include('./templates/menu.tpl.php');

//includes and permissions

switch($page) {
    case 'products_list':
        include('templates/products_list.tpl.php');
        break;
    case 'product_detail':
        include('templates/product_detail.tpl.php');
        break;
    case 'shipping':
        include('templates/shipping.tpl.php');
        break;
    case 'contacts':
        include('templates/contacts.tpl.php');
        break;
    case 'client':
        if (isset($_SESSION['isAuth']) && $_SESSION['isAuth'] == true && isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] == false){
            include('templates/client.tpl.php');
        }
        else header("Location: main.php?page=home");
        break;   
    case 'admin':
        if (isset($_SESSION['isAuth']) && $_SESSION['isAuth'] == true && isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] == true){
            include('templates/admin.tpl.php');
        }
        else header("Location: main.php?page=home");
        break;  
    case 'login':
        if (!isset($_SESSION['isAuth']) || $_SESSION['isAuth'] == false){
            include('templates/login.tpl.php');
        }
        else header("Location: main.php?page=home");
        break;
    case 'sign_up':
        if (!isset($_SESSION['isAuth']) || $_SESSION['isAuth'] == false){
            include('templates/sign_up.tpl.php');
        }
        else header("Location: main.php?page=home");
        break;
    case 'logout':
        include('templates/logout.tpl.php');
        break;
    case 'order_details':
        if (isset($_SESSION['isAuth']) && $_SESSION['isAuth'] == true && isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] == true){
            include('templates/order_details.tpl.php');
        }
        else header("Location: main.php?page=home");
        break;
    case 'home':
    default:
        include('templates/home.tpl.php');
        break;
}


?>
<footer>
    <?php echo 'Page generated on '.date("F j, Y").' at '.date("H:i:s").'';?>
</footer>
<script> </script> <!--So serve para corrigir um bug no chrome onde uma animação começa ao fazer load da página--> 
</body>
</html>