<?php
    include('../includes/config.php');
    include('../includes/functions.php');

    $_SESSION['username'] = htmlentities($_POST["username"]);
    $password = hash("sha256", $_POST["password"]);

    //echo "<br> {$_SESSION['username']} <br>";
    //var_dump($_SESSION['username']);

    $conn = dbconn();

    $result = pg_prepare($conn, "query", "SELECT password, is_admin FROM client WHERE username=$1");
    $result = pg_execute($conn, "query", array("{$_SESSION['username']}"));
    $row = pg_fetch_assoc($result);

    //var_dump($row);

    if (!isset($row['password'])){
        //wrong username
        $_SESSION['error'] = 1;
        header("Location: ../main.php?page=login");
    }
    else if ($row['password'] != $password){
        //wrong password
        $_SESSION['error'] = 2;
        header("Location: ../main.php?page=login");
    }
    else {
        //correct credentials
        $_SESSION['isAuth'] = true;
        if ($row['is_admin'] == "t"){
            $_SESSION['isAdmin'] = true;
        }
        else $_SESSION['isAdmin'] = false;
        header("Location: ../main.php?page=home");
    }
    
    // echo "<br>error = {$_SESSION['error']} <br>";
    // echo "<br>auth = {$_SESSION['Auth']} <br>";
    // echo "<br>admin = {$_SESSION['Admin']} <br>";
?>