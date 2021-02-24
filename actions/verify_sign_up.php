<?php
    include('../includes/config.php');
    include('../includes/functions.php');

    $_SESSION['username'] = htmlentities($_POST["username"]);
    $_SESSION['email'] = htmlentities($_POST['email']);
    $password = hash("sha256", $_POST["password"]);
    $confirm_password = hash("sha256", $_POST["confirm_password"]);

    $conn = dbconn();

    $result = pg_prepare($conn, "query", "SELECT username, password, is_admin FROM client WHERE username=$1");
    $result = pg_execute($conn, "query", array($_SESSION['username']));
    $row = pg_fetch_assoc($result);
    //var_dump($row);

    $result = pg_prepare($conn, "query2", "SELECT email FROM client WHERE email=$1");
    $result = pg_execute($conn, "query2", array($_SESSION['email']));
    $row2 = pg_fetch_assoc($result);
    //var_dump($row2);

    if (isset($row['username']) && !isset($row2['email'])){
        //username already exists and email does not
        $_SESSION['error'] = 1;
        header("Location: ../main.php?page=sign_up");
    }
    else if (!isset($row['username']) && isset($row2['email'])){
        //email already exists and username does not
        $_SESSION['error'] = 2;
        header("Location: ../main.php?page=sign_up");
    }
    else if (isset($row['username']) && isset($row2['email'])){
        //both username and email already exist
        $_SESSION['error'] = 3;
        header("Location: ../main.php?page=sign_up");
    }
    else {
        if ($password != $confirm_password){
            //password and corfirm_password do not match
            $_SESSION['error'] = 4;
            header("Location: ../main.php?page=sign_up");
        }
        else {
            //valid credentials
            $result = pg_prepare($conn, "query3", "INSERT INTO client(username, email, password, name, address, phone, is_admin) values ($1, $2, '{$password}' , '', '', '', 'f')");     
            $result = pg_execute($conn, "query3", array($_SESSION['username'], $_SESSION['email']));

            $_SESSION['new_account'] = true;
            $_SESSION['isAuth'] = true;
            $_SESSION['isAdmin'] = false;
            header("Location: ../main.php?page=home");   
        }
    }
    
    // echo "<br>error = {$_SESSION['error']} <br>";
    // echo "<br>auth = {$_SESSION['Auth']} <br>";
?>