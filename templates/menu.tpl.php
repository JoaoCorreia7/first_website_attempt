<nav>
    <img id="logo_image" src="images/logo.png" alt="logo">
    <p id="logo_text">Virtuoso</p>
    <ul>
        <li <?php echo menuHighlight('home'); ?>><a href="main.php?page=home">Home</a></li>
        <li <?php echo menuHighlight('products_list'); ?>><a href="main.php?page=products_list">Products</a></li>
        <li <?php echo menuHighlight('shipping'); ?>><a href="main.php?page=shipping">Shipping</a></li>
        <li <?php echo menuHighlight('contacts'); ?>><a href="main.php?page=contacts">Contacts</a></li>
        <?php
        if (isset($_SESSION['isAuth']) && $_SESSION['isAuth'] == true) {
            if ($_SESSION['isAdmin'] == true) {
                echo '<li '.menuHighlight('admin') .'><a href="main.php?page=admin">Admin</a></li>';
            }
            else {
                echo '<li '.menuHighlight('client') .'><a href="main.php?page=client">' .$_SESSION['username']. '</a></li>';
            }
            echo '<li><a href="main.php?page=logout">Logout</a></li>';
        } 
        else {
            echo '<li '.menuHighlight('login') .'><a href="main.php?page=login">Login</a></li>';
        }
        ?>
    </ul>
</nav>