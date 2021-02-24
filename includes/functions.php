<?php

include_once('config.php');

function dbconn(){

    global $dbHost, $dbUser, $dbPassword, $dbName;

    $str = "host={$dbHost} dbname={$dbName} user={$dbUser} password={$dbPassword}";
    $conn = pg_connect($str);

    if (!$conn){
        echo "Error connecting to DB";
    }

    $query = "SET SCHEMA 't2'";
    pg_exec($query);
    return $conn;
}

function menuHighlight($menu) {
    global $page;
    if ($page === NULL && $menu === 'home') {
        return 'id="selected_page"';
    } else if ($page === $menu) {
        return 'id="selected_page"';
    }
    return '';
}

function getProductById($id) {
    dbconn();
    $query = "SELECT product_id, 
                    name,  
                    price,
                    stock, 
                    description, 
                    image_array[1] AS image1, 
                    image_array[2] AS image2, 
                    image_array[3] AS image3, 
                    image_array[4] AS image4 FROM product WHERE product_id = {$id}";
    $result = pg_exec($query);
    $row = pg_fetch_assoc($result);
    return $row;
}


function alert($msg) {
    echo "<script type='text/javascript'>alert('$msg');</script>";
    return NULL;
}
?>